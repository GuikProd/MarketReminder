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

namespace App\GCP\CloudStorage;

use App\GCP\CloudStorage\Interfaces\CloudStorageCleanerHelperInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;

/**
 * Class CloudStorageCleanerHelper;
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageCleanerHelper implements CloudStorageCleanerHelperInterface
{
    /**
     * @var CloudStorageBridgeInterface
     */
    private $cloudStorageBridgeInterface;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudStorageBridgeInterface $cloudStorageBridgeInterface)
    {
        $this->cloudStorageBridgeInterface = $cloudStorageBridgeInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $bucketName, string $fileName): void
    {
        $this->cloudStorageBridgeInterface
             ->loadCredentialsFile()
             ->getServiceBuilder()
             ->storage()
             ->bucket($bucketName)
             ->object($fileName)
             ->delete();
    }
}
