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

use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces\CloudTranslationYamlParserInterface;

/**
 * Interface CloudTranslationTranslatorInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationTranslatorInterface
{
    /**
     * CloudTranslationTranslatorInterface constructor.
     *
     * @param string $translationsFolder
     * @param CloudTranslationClientInterface $cloudTranslationClient
     * @param CloudTranslationYamlParserInterface $cloudTranslationYamlParser
     */
    public function __construct(
        string $translationsFolder,
        CloudTranslationClientInterface $cloudTranslationClient,
        CloudTranslationYamlParserInterface $cloudTranslationYamlParser
    );

    /**
     * @param string $locale
     * @param string $channel
     *
     * @return void
     */
    public function warmTranslations(string $locale, string $channel): void;
}
