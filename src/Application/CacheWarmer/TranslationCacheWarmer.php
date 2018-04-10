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

namespace App\Application\CacheWarmer;

use App\Application\CacheWarmer\Interfaces\TranslationCacheWarmerInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class TranslationCacheWarmer.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TranslationCacheWarmer extends CacheWarmer implements TranslationCacheWarmerInterface, CacheWarmerInterface
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
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $finder = new Finder();
        $fileSystem = new Filesystem();
        $yaml = new Yaml();

        $fileSystem->mkdir($cacheDir.'/googleCloud');

        $files = $finder->files()->in($this->translationsFolder);

        $translatedElements = [];

        foreach ($files as $file) {
            $content = $yaml::parse($file->getContents());

            foreach ($content as $key => $translation) {

                foreach (explode('|', $this->acceptedLocales) as $locale) {

                    $translatedContent = $this->cloudTranslationWarmer->warmTranslation($translation, $locale);
                    $translatedElements[$locale][$key] = $translatedContent['text'];
                }
            }
        }

        $this->writeCacheFile($cacheDir.'/googleCloud/translations.php', serialize($translatedElements));

        file_put_contents(
            $this->translationsFolder.'/google_cloud.%s.yaml',
            Yaml::dump(
                unserialize(
                    file_get_contents($cacheDir.'/googleCloud/translations.php')
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return false;
    }
}
