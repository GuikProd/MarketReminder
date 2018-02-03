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

use Google\Cloud\Storage\StorageObject;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Helper\Interfaces\CloudStorage\CloudStoragePersisterHelperInterface;

/**
 * Class CloudStoragePersisterHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStoragePersisterHelper implements CloudStoragePersisterHelperInterface
{
    /**
     * @var CloudStorageBridgeInterface
     */
    private $cloudStorageBridge;

    /**
     * CloudStoragePersisterHelper constructor.
     *
     * @param CloudStorageBridgeInterface $cloudStorageBridge
     */
    public function __construct(CloudStorageBridgeInterface $cloudStorageBridge)
    {
        $this->cloudStorageBridge = $cloudStorageBridge;
    }

    /**
     * {@inheritdoc}
     */
    public function persist(string $bucketName, string $fileName, $options = []): StorageObject
    {
        return $this->cloudStorageBridge
                    ->loadCredentialsFile()
                    ->getServiceBuilder()
                    ->storage()
                    ->bucket($bucketName)
                    ->upload(fopen($fileName, 'r'), $options);
    }
}
