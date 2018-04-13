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
class TranslationWarmerCommand extends Command implements TranslationWarmerCommandInterface
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
     * @var string
     */
    private $translationsFolder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $acceptedLocales,
        CloudTranslationWarmerInterface $cloudTranslationWarmer,
        string $translationsFolder
    ) {
        $this->acceptedLocales = $acceptedLocales;
        $this->cloudTranslationWarmer = $cloudTranslationWarmer;
        $this->translationsFolder = $translationsFolder;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('app:translation-warm')
            ->setDescription('Allow to warm the translation')
            ->setHelp('This command call the GCP Translation API and translate the whole file passed.')
            ->addArgument('filename', InputArgument::REQUIRED, 'The complete name of the file to translate.')
            ->addArgument('locale', InputArgument::REQUIRED, 'The locale used by the file to translate.')
            ->addArgument('destinationLocale', InputArgument::REQUIRED, 'The estination locale used to translate.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('The translations are loaded and sent to GCP.');

        $finder = new Finder();
        
        $files = $finder->files()
                        ->in($this->translationsFolder)
                        ->name($input->getArgument('filename').'.'.$input->getArgument('locale').'.yaml');

        $acceptedLocales = explode('|', $this->acceptedLocales);
        $translatedElements = [];
        $toTranslateElements = [];
        $toTranslateKeys = [];

        if ('fr' === $acceptedLocales[0]) {
            unset($acceptedLocales[0]);
        }

        foreach ($files as $file) {

            $content = Yaml::parse($file->getContents());

            foreach ($content as $value => $entry) {
                $toTranslateElements[] = $entry;
                $toTranslateKeys[] = $value;
            }
        }

        foreach ($acceptedLocales as $locale) {
            $values = $this->cloudTranslationWarmer->warmArrayTranslation($toTranslateElements, $locale);

            foreach ($values as $value) {
                $translatedElements[] = $value['text'];
            }
        }

        $finalArray = array_combine($toTranslateKeys, $translatedElements);

        Yaml::dump(
            file_put_contents($this->translationsFolder.'/'.$input->getArgument('filename').'.en.'.'yaml', $finalArray)
        );


        $output->writeln('The translations has been translated and dumped into the translations folder.');
    }
}
