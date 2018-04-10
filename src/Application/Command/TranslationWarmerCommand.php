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

    protected function configure()
    {
        $this->setName('app:translation-warm')
             ->setDescription('Allow to warm the translation')
             ->setHelp('This command call the GCP Translation API and translate every text');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('The translations are loaded and sent to GCP.');

        $finder = new Finder();
        $fileSystem = new Filesystem();

        $fileSystem->mkdir($this->translationsFolder.'/google_cloud');

        $files = $finder->files()->in($this->translationsFolder);

        $translatedElements = [];
        $acceptedLocales = explode('|', $this->acceptedLocales);

        if ('fr' === $acceptedLocales[0]) {
            unset($acceptedLocales[0]);
        }

        foreach ($files as $file) {
            $content = Yaml::parse($file->getContents());

            foreach ($content as $key => $translation) {

                foreach ($acceptedLocales as $locale) {

                    $translatedContent = $this->cloudTranslationWarmer->warmTranslation($translation, $locale);
                    $translatedElements[$locale][$key] = $translatedContent['text'];

                }
            }
        }

        foreach ($translatedElements as $element) {
            foreach ($acceptedLocales as $locale) {
                file_put_contents(
                    $this->translationsFolder.\sprintf("/google_cloud/google_cloud.%s.yaml", $locale),
                    Yaml::dump($element)
                );
            }
        }

        $output->writeln('The translations has been translated and dumped into the translations folder.');
    }
}
