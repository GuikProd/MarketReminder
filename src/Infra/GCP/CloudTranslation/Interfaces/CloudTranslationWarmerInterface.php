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

namespace App\Infra\GCP\CloudTranslation\Interfaces;

use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;

/**
 * Interface CloudTranslationWarmerInterface.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudTranslationWarmerInterface
{
    /**
     * CloudTranslationWarmerInterface constructor.
     *
     * @param CloudTranslationBridgeInterface  $cloudTranslationBridge
     */
    public function __construct(CloudTranslationBridgeInterface $cloudTranslationBridge);

    /**
     * @param string  $textToTranslate
     * @param string  $targetLocale
     *
     * @return array|null
     */
    public function warmTranslation(string $textToTranslate, string $targetLocale):? array;

    /**
     * @param array  $textToTranslate
     * @param string $targetLocale
     *
     * @return array|null
     */
    public function warmArrayTranslation(array $textToTranslate, string $targetLocale):? array;
}
