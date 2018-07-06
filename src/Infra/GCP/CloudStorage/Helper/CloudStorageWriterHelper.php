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

namespace App\Infra\GCP\CloudStorage\Helper;

use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use Google\Cloud\Storage\StorageObject;

/**
 * Class CloudStorageWriterHelper.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudStorageWriterHelper implements CloudStorageWriterHelperInterface
{
    /**
     * @var CloudStorageBridgeInterface
     */
    private $cloudStorageBridge;

    /**
     * CloudStorageWriterHelper constructor.
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
                    ->getStorageClient()
                    ->bucket($bucketName)
                    ->upload(fopen($fileName, 'r'), $options);
    }
}
