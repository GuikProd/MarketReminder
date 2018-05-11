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

namespace App\Infra\Redis\Translation;

use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class RedisTranslationWarmer.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RedisTranslationWarmer implements RedisTranslationWarmerInterface
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
        string $acceptedChannels,
        string $acceptedLocales,
        CloudTranslationWarmerInterface $cloudTranslationWarmer,
        RedisTranslationRepositoryInterface $redisTranslationRepository,
        RedisTranslationWriterInterface $redisTranslationWriter,
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
        if (!\in_array($channel, explode('|', $this->acceptedChannels))) {
            throw new \InvalidArgumentException(
                sprintf('This channel does not exist !')
            );
        } elseif (!\in_array($locale, explode('|', $this->acceptedLocales))) {
            throw new \InvalidArgumentException(
                sprintf('This locale isn\'t supported, please retry.')
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
            if (!$cacheStatus = $this->isCacheValid($channel, 'fr', $defaultContent)) {

                if ($this->redisTranslationWriter->write($locale, $channel, $channel.'.fr.yaml', $defaultContent)) {
                    return false;
                }
            }

            if (!$newItem = $this->redisTranslationRepository->getEntries($channel.'.'.$locale.'.yaml')) {

                $translatedElements = [];

                $translatedContent = $this->cloudTranslationWarmer->warmArrayTranslation($toTranslateContent, $locale);

                foreach ($translatedContent as $value) {
                    $translatedElements[] = $value['text'];
                }

                if (!$this->fileExistAndIsValid($channel.'.'.$locale.'.yaml', array_combine($toTranslateKeys, $translatedElements))) {
                    return false;
                }

                file_put_contents(
                    $this->translationsFolder.'/'.$channel.'.'.$locale.'.yaml',
                    Yaml::dump(array_combine($toTranslateKeys, $translatedElements))
                );

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

        if (\count(array_diff($content, $toCheckContent)) > 0) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function fileExistAndIsValid(string $filename, array $translatedContent): bool
    {
        if (file_exists($this->translationsFolder.'/'.$filename)) {
            $fileContent = Yaml::parse(
                file_get_contents($this->translationsFolder.'/'.$filename)
            );

            if (\count(array_diff($fileContent, $translatedContent)) > 0) {
                return false;
            }

            return true;
        }

        return false;
    }
}
