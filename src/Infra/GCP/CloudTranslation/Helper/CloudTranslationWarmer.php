<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infra\GCP\CloudTranslation\Helper;

use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces\CloudTranslationYamlParserInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CloudTranslationWarmer.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
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
     * @var CloudTranslationClientInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $cloudTranslationWriter;

    /**
     * @var CloudTranslationYamlParserInterface
     */
    private $cloudTranslationYamlParser;

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
        CloudTranslationClientInterface $cloudTranslationWarmer,
        CloudTranslationRepositoryInterface $cloudTranslationRepository,
        CloudTranslationWriterInterface $cloudTranslationWriter,
        CloudTranslationYamlParserInterface $cloudTranslationYamlParser,
        string $translationsFolder
    ) {
        $this->acceptedChannels = $acceptedChannels;
        $this->acceptedLocales = $acceptedLocales;
        $this->cloudTranslationWarmer = $cloudTranslationWarmer;
        $this->cloudTranslationRepository = $cloudTranslationRepository;
        $this->cloudTranslationWriter = $cloudTranslationWriter;
        $this->cloudTranslationYamlParser = $cloudTranslationYamlParser;
        $this->translationsFolder = $translationsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function warmTranslations(string $channel, string $locale, string $defaultLocale = 'fr'): bool
    {
        if (!\in_array($channel, explode('|', $this->acceptedChannels))
            || !\in_array($locale, explode('|', $this->acceptedLocales))
        ) {
            throw new \InvalidArgumentException(
                sprintf('The submitted locale is not supported or the channel does not exist, given %s', $locale)
            );
        }

        $toTranslateKeys = [];
        $toTranslateContent = [];

        $defaultContent = Yaml::parse(
            file_get_contents($this->translationsFolder.'/'.$channel.'.'.$defaultLocale.'.yaml')
        );

        foreach ($defaultContent as $item => $value) {
            $toTranslateKeys[] = $item;
            $toTranslateContent[] = $value;
        }

        try {
            if (!$this->isCacheValid($channel, $defaultLocale, $defaultContent)) {

                if (!$this->cloudTranslationWriter->write(
                    $defaultLocale,
                    $channel,
                    $channel . '.' . $defaultLocale . '.yaml',
                    $defaultContent)
                ) {
                    // If the cache is already in place, no need to stop the process.
                }
            }

            if ($this->checkNewFileExistenceAndValidity($channel . '.' . $locale . '.yaml', $toTranslateKeys)) {
                if ($this->isCacheValid($channel, $locale, $toTranslateContent)) {
                    return true;
                }

                $this->cloudTranslationYamlParser->parseYaml($this->translationsFolder, $channel.'.'.$locale);

                return $this->cloudTranslationWriter->write(
                    $locale,
                    $channel,
                    $channel . '.' . $locale . '.yaml',
                    array_combine($this->cloudTranslationYamlParser->getKeys(), $this->cloudTranslationYamlParser->getValues())
                );
            }

            if (!$newItem = $this->cloudTranslationRepository->getEntries($channel.'.'.$locale.'.yaml')) {

                $translatedElements = [];

                $translatedContent = $this->cloudTranslationWarmer->translateArray($toTranslateContent, $locale);

                foreach ($translatedContent as $value) {
                    $translatedElements[] = $value['text'];
                }

                if ('fr' !== $locale) {
                    file_put_contents(
                        $this->translationsFolder . '/' . $channel . '.' . $locale . '.yaml',
                        Yaml::dump(array_combine($toTranslateKeys, $translatedElements))
                    );
                }

                return $this->cloudTranslationWriter->write(
                    $locale,
                    $channel,
                    $channel . '.' . $locale . '.yaml',
                    array_combine($toTranslateKeys, $translatedElements)
                );
            }

            return false;

        } catch (InvalidArgumentException $e) {
            sprintf($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCacheValid(string $channel, string $locale, array $content): bool
    {
        $toCheckContent = [];

        if (!$cacheContent = $this->cloudTranslationRepository->getEntries($channel.'.'.$locale.'.yaml')) {
            return false;
        }

        foreach ($cacheContent as $item => $value) {
            $toCheckContent[$value->getKey()] = $value->getValue();
        }

        return \count(array_diff($content, $toCheckContent)) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function checkNewFileExistenceAndValidity(string $filename, array $translatedKeys): bool
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
