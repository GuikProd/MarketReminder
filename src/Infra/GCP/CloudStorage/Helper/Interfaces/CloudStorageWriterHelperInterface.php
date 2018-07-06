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

namespace App\Infra\GCP\CloudStorage\Helper\Interfaces;

use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use Google\Cloud\Storage\StorageObject;

/**
 * Interface CloudStorageWriterHelperInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudStorageWriterHelperInterface
{
    /**
     * CloudStorageWriterHelperInterface constructor.
     *
     * @param CloudStorageBridgeInterface $cloudStorageBridge
     */
    public function __construct(CloudStorageBridgeInterface $cloudStorageBridge);

    /**
     * Allow to persist a new file into a given bucket.
     *
     * @param string  $bucketName    The name of the bucket.
     * @param string  $fileName      The name of the file.
     * @param array   $options       The options used during the upload.
     *
     * @return StorageObject
     */
    public function persist(string $bucketName, string $fileName, $options = []): StorageObject;
}
