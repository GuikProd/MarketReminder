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

use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces\CloudTranslationYamlParserInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CloudTranslationWarmer.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWarmer implements CloudTranslationWarmerInterface
{
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
        string $translationsFolder,
        CloudTranslationWriterInterface $cloudTranslationWriter,
        CloudTranslationYamlParserInterface $cloudTranslationYamlParser
    ) {
        $this->translationsFolder = $translationsFolder;
        $this->cloudTranslationWriter = $cloudTranslationWriter;
        $this->cloudTranslationYamlParser = $cloudTranslationYamlParser;
    }

    /**
     * {@inheritdoc}
     */
    public function warmTranslationsCache(string $channel, string $locale): void
    {
        $this->cloudTranslationYamlParser->parseYaml($this->translationsFolder, $channel.'.'.$locale);

        try {
            $this->cloudTranslationWriter->write(
                $locale,
                $channel,
                $channel.'.'.$locale.'.yaml',
                $this->cloudTranslationYamlParser->getFinalArray()
            );
        } catch (InvalidArgumentException $e) {
            sprintf($e->getMessage());
        }
    }
}
