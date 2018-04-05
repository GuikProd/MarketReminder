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

namespace App\Infra\GCP\CloudStorage\Interfaces;

use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
use Google\Cloud\Storage\StorageObject;

/**
 * Interface CloudStoragePersisterHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudStoragePersisterHelperInterface
{
    /**
     * CloudStoragePersisterHelperInterface constructor.
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
