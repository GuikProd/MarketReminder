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

namespace App\Infra\GCP\CloudTranslation;

use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationHelperInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CloudTranslationWarmer.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class CloudTranslationWarmer implements CloudTranslationWarmerInterface
{
    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * @var string
     */
    private $acceptedChannels;

    /**
     * @var CloudTranslationHelperInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationWriterInterface
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
        string $acceptedChannels,
        string $acceptedLocales,
        CloudTranslationHelperInterface $cloudTranslationWarmer,
        CloudTranslationRepositoryInterface $redisTranslationRepository,
        CloudTranslationWriterInterface $redisTranslationWriter,
        string $translationsFolder
    ) {
        $this->acceptedChannels = $acceptedChannels;
        $this->acceptedLocales = $acceptedLocales;
        $this->cloudTranslationWarmer = $cloudTranslationWarmer;
        $this->redisTranslationRepository = $redisTranslationRepository;
        $this->redisTranslationWriter = $redisTranslationWriter;
        $this->translationsFolder = $translationsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function warmTranslations(string $channel, string $locale): bool
    {
        if (!\in_array($channel, explode('|', $this->acceptedChannels))
            || !\in_array($locale, explode('|', $this->acceptedLocales))
        ) {
            throw new \InvalidArgumentException(
                sprintf('The submitted locale isn\'t supported or the channel does not exist !')
            );
        }

        $toTranslateKeys = [];
        $toTranslateContent = [];

        $defaultContent = Yaml::parse(
            file_get_contents($this->translationsFolder.'/'.$channel.'.fr.yaml')
        );

        foreach ($defaultContent as $item => $value) {
            $toTranslateKeys[] = $item;
            $toTranslateContent[] = $value;
        }

        try {
            if (!$this->isCacheValid($channel, 'fr', $defaultContent)) {
                if (!$this->redisTranslationWriter->write('fr', $channel, $channel.'fr.yaml', $defaultContent)) {
                    // If the cache already contain the "fr" entries, the process continue.
                }
            }

            if (!$this->fileExistAndIsValid($channel.'.'.$locale.'.yaml', $toTranslateKeys)) {
                return false;
            }

            if (!$newItem = $this->redisTranslationRepository->getEntries($channel.'.'.$locale.'.yaml')) {

                $translatedElements = [];

                $translatedContent = $this->cloudTranslationWarmer->warmArrayTranslation($toTranslateContent, $locale);

                foreach ($translatedContent as $value) {
                    $translatedElements[] = $value['text'];
                }

                if ('fr' !== $locale) {
                    file_put_contents(
                        $this->translationsFolder.'/'.$channel.'.'.$locale.'.yaml',
                        Yaml::dump(array_combine($toTranslateKeys, $translatedElements))
                    );
                }

                $this->redisTranslationWriter->write(
                    $locale,
                    $channel,
                    $channel.'.'.$locale.'.yaml',
                    array_combine($toTranslateKeys, $translatedElements)
                );
            }

        } catch (\Psr\Cache\InvalidArgumentException $exception) {
            sprintf($exception->getMessage());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCacheValid(string $channel, string $locale, array $content): bool
    {
        $toCheckContent = [];

        if (!$cacheContent = $this->redisTranslationRepository->getEntries($channel.'.'.$locale.'.yaml')) {
            return false;
        }

        foreach ($cacheContent as $item => $value) {
            $toCheckContent[$value->getKey()] = $value->getValue();
        }

        return \count(array_diff($content, $toCheckContent)) > 0 ? false : true;
    }

    /**
     * {@inheritdoc}
     */
    public function fileExistAndIsValid(string $filename, array $translatedKeys): bool
    {
        if (file_exists($this->translationsFolder.'/'.$filename)) {

            $actualKeys = [];

            $fileContent = Yaml::parse(
                file_get_contents($this->translationsFolder . '/' . $filename)
            );

            foreach ($fileContent as $item => $value) {
                $actualKeys[] = $item;
            }

            return \count(array_diff($actualKeys, $translatedKeys)) > 0 ? false : true;
        }

        return false;
    }
}
