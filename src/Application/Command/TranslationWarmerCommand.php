<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Command;

use App\Application\Command\Interfaces\TranslationWarmerCommandInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class TranslationWarmerCommand.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class TranslationWarmerCommand extends Command implements TranslationWarmerCommandInterface
{
    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var RedisTranslationWriterInterface
     */
    private $redisTranslationWriter;

    /**
     * @var string
     */
    private $translationsFolder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $acceptedLocales,
        CloudTranslationWarmerInterface $cloudTranslationWarmer,
        RedisTranslationRepositoryInterface $redisTranslationRepository,
        RedisTranslationWriterInterface $redisTranslationWriter,
        string $translationsFolder
    ) {
        $this->acceptedLocales = $acceptedLocales;
        $this->cloudTranslationWarmer = $cloudTranslationWarmer;
        $this->redisTranslationRepository = $redisTranslationRepository;
        $this->redisTranslationWriter = $redisTranslationWriter;
        $this->translationsFolder = $translationsFolder;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:translation-warm')
            ->setDescription('Allow to warm the translation for a given channel and locale.')
            ->setHelp('This command call the GCP Translation API and translate (using the locale passed) the whole channel passed.')
            ->addArgument('channel', InputArgument::REQUIRED, 'The channel of the file to translate.')
            ->addArgument('locale', InputArgument::REQUIRED, 'The locale used to translate.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>The translations file are loaded and parsed.</info>');

        $finder = new Finder();

        $files = $finder->files()
                        ->in($this->translationsFolder)
                        ->name(
                            $input->getArgument('channel').'.fr.yaml'
                        );

        if (!$files->count() > 0) {
            throw new \InvalidArgumentException(\sprintf('This channel does not exist, please retry !'));
        }

        if (!\in_array($input->getArgument('locale'), explode('|', $this->acceptedLocales))) {
            $output->writeln(
                "<comment>The locale isn't defined in the accepted locales, the generated files could not be available.</comment>"
            );
        }

        $translatedElements = [];
        $toTranslateElements = [];
        $toTranslateKeys = [];

        foreach ($files as $file) {

            try {
                $this->checkRedisTranslationCache($output, $file->getFilename(), $file);
            } catch (\Psr\Cache\InvalidArgumentException $exception) {
                $output->write('<error>'.$exception->getMessage().'</error>');
            }

            try {
                $this->warmRedisTranslationCache($input->getArgument('channel'), $output, $file);
            } catch (\Psr\Cache\InvalidArgumentException $exception) {
                $output->write('<error>'. $exception->getMessage() . '</error>');
            }

            if ($this->checkFileContent($output, $input->getArgument('channel'), $input->getArgument('locale'), $file)) {
                return;
            }

            $this->backUpTranslation($output, $file);

            $content = Yaml::parse($file->getContents());

            foreach ($content as $value => $entry) {
                $toTranslateElements[] = $entry;
                $toTranslateKeys[] = $value;
            }
        }

        $output->writeln('<info>The translations keys are about to be translated.</info>');

        $values = $this->cloudTranslationWarmer->warmArrayTranslation($toTranslateElements, $input->getArgument('locale'));

        foreach ($values as $value) {
            $translatedElements[] = $value['text'];
        }

        file_put_contents(
            $this->translationsFolder.'/'.$input->getArgument('channel').'.'.$input->getArgument('locale').'.yaml',
            Yaml::dump(
                array_combine($toTranslateKeys, $translatedElements)
            )
        );

        $output->writeln('<info>The translations has been translated and dumped into the translations folder.</info>');
    }

    /**
     * {@inheritdoc}
     */
    public function warmRedisTranslationCache(string $channel, OutputInterface $output, \SplFileInfo $toWarmFile): bool
    {
        $content = Yaml::parse($toWarmFile->getContents());

        if ($this->redisTranslationWriter->write($channel, $toWarmFile->getFilename(), $content)) {
            $output->write('<info>The translations has been cached.</info>');

            return true;
        }

        $output->write('<info>The translations are already cached, process skipped.</info>');

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function checkRedisTranslationCache(OutputInterface $output, string $fileName, \SplFileInfo $toCompareFile): bool
    {
        $toCheckContent = Yaml::parse($toCompareFile->getContents());

        if (!$cachedContent = $this->redisTranslationRepository->getEntry($fileName)) {
            return false;
        }

        dump($cachedContent);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function backUpTranslation(OutputInterface $output, \SplFileInfo $toBackUpFile): void
    {
        $fileSystem = new Filesystem();
        $finder = new Finder();
        $fileSystem->mkdir($this->translationsFolder.'/backup');

        $files = $finder->files()
                        ->in($this->translationsFolder.'/backup');

        foreach ($files as $file) {
            if (Yaml::parse($toBackUpFile->getContents()) === Yaml::parse($file->getContents())) {
                return;
            }
        }

        file_put_contents(
            $this->translationsFolder.'/backup/'.time().$toBackUpFile->getBasename(),
            Yaml::dump(
                Yaml::parse($toBackUpFile->getContents())
            )
        );

        $output->writeln('<info>The default content of the file has been saved in the backup.</info>');
    }

    /**
     * {@inheritdoc}
     */
    public function checkFileContent(OutputInterface $output, string $channel, string $locale, \SplFileInfo $toCompareFile): bool
    {
        $finder = new Finder();

        $files = $finder->files()
                        ->in($this->translationsFolder)
                        ->name($channel.'.'.$locale.'.yaml');

        if (count($files) < 1) {
            $output->writeln(
                '<info>No default file has been found with the translated content, the translation process is in progress.</info>'
            );

            return false;
        }

        $defaultKeys = [];
        $toCompareKeys = [];

        foreach ($files as $file) {
            $content = Yaml::parse($file->getContents());
            $defaultContent = Yaml::parse($toCompareFile->getContents());

            foreach ($defaultContent as $key => $value) {
                $defaultKeys[] = $key;
            }

            foreach ($content as $key => $value) {
                $toCompareKeys[] = $key;
            }

            if (\array_diff($defaultKeys, $toCompareKeys)) {
                return false;
            } else if (!\array_diff($defaultKeys, $toCompareKeys)) {
                $output->writeln(
                    '<info>The default files already contains the translated content, the translation process is skipped.</info>'
                );

                return true;
            }
        }

        return false;
    }
}
