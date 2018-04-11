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
             ->setHelp('This command call the GCP Translation API and translate every text');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('The translations are loaded and sent to GCP.');

        $finder = new Finder();
        $fileSystem = new Filesystem();

        $fileSystem->mkdir($this->translationsFolder.'/google_cloud');

        $files = $finder->files()->in($this->translationsFolder);

        $defaultFilename = [];
        $translatedElements = [];
        $acceptedLocales = explode('|', $this->acceptedLocales);

        if ('fr' === $acceptedLocales[0]) {
            unset($acceptedLocales[0]);
        }

        foreach ($files as $file) {

            $defaultFilename[] = $file->getFilename();
            $content = Yaml::parse($file->getContents());

            foreach ($content as $key => $translation) {

                foreach ($acceptedLocales as $locale) {

                    $translatedElements[$locale][$file->getFilename()][$key] = $this->cloudTranslationWarmer->warmTranslation($translation, $locale)['text'];

                }
            }
        }

        dump($translatedElements);
        die();

        foreach ($translatedElements as $element) {
            foreach ($acceptedLocales as $locale) {
                foreach ($defaultFilename as $filename) {
                    if (!strstr($locale, $filename)) {
                        file_put_contents(
                            $this->translationsFolder.\sprintf("/%s1.%s2.yaml", $filename, $locale),
                            Yaml::dump($element)
                        );
                    }
                }
            }
        }

        $output->writeln('The translations has been translated and dumped into the translations folder.');
    }
}
