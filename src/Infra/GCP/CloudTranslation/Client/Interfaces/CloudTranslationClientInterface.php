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

namespace App\Infra\GCP\CloudTranslation\Client\Interfaces;

use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;

/**
 * Interface CloudTranslationClientInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationClientInterface
{
    /**
     * CloudTranslationClientInterface constructor.
     *
     * @param CloudTranslationBridgeInterface  $cloudTranslationBridge
     */
    public function __construct(CloudTranslationBridgeInterface $cloudTranslationBridge);

    /**
     * @param string $entry
     * @param string $targetLocale
     *
     * @return string
     */
    public function translateSingleEntry(string $entry, string $targetLocale): string;

    /**
     * @param array  $textToTranslate
     * @param string $targetLocale
     *
     * @return array|null
     */
    public function translateArray(array $textToTranslate, string $targetLocale):? array;
}
