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

namespace App\Tests\TestCase;

use App\Infra\GCP\CloudStorage\Bridge\CloudStorageBridge;
use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudVision\Bridge\CloudVisionBridge;
use App\Infra\GCP\CloudVision\Bridge\Interfaces\CloudVisionBridgeInterface;

/**
 * Trait CloudBridgeTrait.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
trait CloudBridgeTrait
{
    use CredentialsLoaderTrait;

    /**
     * @var CloudStorageBridgeInterface
     */
    private $storageBridge;

    /**
     * @var CloudTranslationBridgeInterface
     */
    private $translationBridge;

    /**
     * @var CloudVisionBridgeInterface
     */
    private $visionBridge;

    public function createStorageBridge(string $filename)
    {
        $this->createCredentialsLoader();

        $this->storageBridge = new CloudStorageBridge(
            $filename,
            __DIR__.'/../_credentials',
            $this->loader
        );
    }

    public function createTranslationBridge(string $filename)
    {
        $this->createCredentialsLoader();

        $this->translationBridge = new CloudTranslationBridge(
            $filename,
            __DIR__.'/../_credentials',
            $this->loader
        );
    }

    public function createVisionBridge(string $filename)
    {
        $this->createCredentialsLoader();

        $this->visionBridge = new CloudVisionBridge(
            $filename,
            __DIR__.'/../_credentials',
            $this->loader
        );
    }
}
