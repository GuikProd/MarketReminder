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

namespace App\Infra\GCP\CloudTranslation\Helper\Interfaces;

use App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces\CloudTranslationYamlParserInterface;

/**
 * Interface CloudTranslationWarmerInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationWarmerInterface
{
    /**
     * CloudTranslationWarmerInterface constructor.
     *
     * @param string $translationsFolder
     * @param CloudTranslationWriterInterface $cloudTranslationWriter
     * @param CloudTranslationYamlParserInterface $cloudTranslationYamlParser
     */
    public function __construct(
        string $translationsFolder,
        CloudTranslationWriterInterface $cloudTranslationWriter,
        CloudTranslationYamlParserInterface $cloudTranslationYamlParser
    );

    /**
     * @param string $channel
     * @param string $locale
     */
    public function warmTranslationsCache(string $channel, string $locale): void;
}
