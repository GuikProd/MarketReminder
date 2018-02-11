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

namespace App\Helper\CloudStorage;

use App\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Helper\Interfaces\CloudStorage\CloudStorageCleanerHelperInterface;

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
     * CloudStorageCleanerHelper constructor.
     *
     * @param CloudStorageBridgeInterface $cloudStorageBridgeInterface
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
