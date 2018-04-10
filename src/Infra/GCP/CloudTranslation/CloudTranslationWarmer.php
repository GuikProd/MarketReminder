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
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;

/**
 * Class CloudTranslationWarmer.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationWarmer implements CloudTranslationWarmerInterface
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
                    ->loadCredentialsFile()
                    ->getServiceBuilder()
                    ->translate()
                    ->translate($textToTranslate, ['target' => $targetLocale]);
    }
}
