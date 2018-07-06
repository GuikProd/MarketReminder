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
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationTranslatorInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces\CloudTranslationYamlParserInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CloudTranslationTranslator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationTranslator implements CloudTranslationTranslatorInterface
{
    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationClient;

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
        string $translationsFolder,
        CloudTranslationClientInterface $cloudTranslationClient,
        CloudTranslationYamlParserInterface $cloudTranslationYamlParser
    ) {
        $this->translationsFolder = $translationsFolder;
        $this->cloudTranslationClient = $cloudTranslationClient;
        $this->cloudTranslationYamlParser = $cloudTranslationYamlParser;
    }

    /**
     * {@inheritdoc}
     */
    public function warmTranslations(string $locale, string $channel): void
    {
        $this->cloudTranslationYamlParser->parseYaml($this->translationsFolder, $channel.'.fr');

        if (!$this->checkNewFileExistenceAndValidity($channel.'.'.$locale.'.yaml', $this->cloudTranslationYamlParser->getKeys())) {

            $translatedElements = [];

            $translatedContent = $this->cloudTranslationClient->translateArray(
                $this->cloudTranslationYamlParser->getValues(),
                $locale
            );

            foreach ($translatedContent as $value) {
                $translatedElements[] = $value['text'];
            }

            file_put_contents(
                $this->translationsFolder . '/' . $channel . '.' . $locale . '.yaml',
                Yaml::dump(array_combine($this->cloudTranslationYamlParser->getKeys(), $translatedElements))
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    private function checkNewFileExistenceAndValidity(string $filename, array $translatedKeys): bool
    {
        if (file_exists($this->translationsFolder.'/'.$filename)) {

            $actualKeys = [];

            $fileContent = Yaml::parse(
                file_get_contents($this->translationsFolder . '/' . $filename)
            );

            foreach ($fileContent as $item => $value) {
                $actualKeys[] = $item;
            }

            return \count(array_diff($translatedKeys, $actualKeys)) > 0 ? false : true;
        }

        return false;
    }
}
