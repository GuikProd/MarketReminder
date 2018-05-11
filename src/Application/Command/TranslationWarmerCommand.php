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

        $files = $finder->files()->in($this->translationsFolder)
                                 ->name($input->getArgument('channel').'.fr.yaml');

        if (!$files->count() > 0) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'This channel does not exist, please retry !'
                )
            );
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
                if (!$this->checkRedisTranslationCache($output, $file->getFilename(), $file)) {
                    $this->warmRedisTranslationCache(
                        $input->getArgument('locale'),
                        $input->getArgument('channel'),
                        $output,
                        $file
                    );
                }
            } catch (\Psr\Cache\InvalidArgumentException $exception) {
                $output->write('<error>'.$exception->getMessage().'</error>');
            }

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
    public function warmRedisTranslationCache(string $locale, string $channel, OutputInterface $output, \SplFileInfo $toWarmFile): bool
    {
        if ($this->redisTranslationWriter->write($locale, $channel, $toWarmFile->getFilename(), Yaml::parse($toWarmFile->getContents()))) {
            $output->write('<info>The translations are been cached.</info>');

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
        $cachedTranslations = [];

        if (!$cachedContent = $this->redisTranslationRepository->getEntries($fileName)) {
            return false;
        }

        foreach ($cachedContent as $item => $value) {
            $cachedTranslations[$value->getKey()] = $value->getValue();
        }

        if (count(array_diff(Yaml::parse($toCompareFile->getContents()), $cachedTranslations)) > 0) {
            return false;
        }

        return true;
    }
}
