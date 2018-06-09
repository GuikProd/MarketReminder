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

namespace App\Infra\GCP\CloudTranslation\Client;

use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;

/**
 * Class CloudTranslationClient.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationClient implements CloudTranslationClientInterface
{
    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudTranslationBridgeInterface $cloudTranslationBridge)
    {
        $this->cloudTranslationBridge = $cloudTranslationBridge;
    }

    /**
     * {@inheritdoc}
     */
    public function translateArray(array $textToTranslate, string $targetLocale): ?array
    {
        return $this->cloudTranslationBridge
                    ->getTranslateClient()
                    ->translateBatch($textToTranslate, ['target' => $targetLocale]);
    }
}
