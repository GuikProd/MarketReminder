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

use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationHelperInterface;

/**
 * Class CloudTranslationHelper.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class CloudTranslationHelper implements CloudTranslationHelperInterface
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
    public function warmTranslation(string $textToTranslate, string $targetLocale):? array
    {
        return $this->cloudTranslationBridge
                    ->getTranslateClient()
                    ->translate($textToTranslate, ['target' => $targetLocale]);
    }

    /**
     * {@inheritdoc}
     */
    public function warmArrayTranslation(array $textToTranslate, string $targetLocale): ? array
    {
        return $this->cloudTranslationBridge
                    ->getTranslateClient()
                    ->translateBatch($textToTranslate, ['target' => $targetLocale]);
    }
}
