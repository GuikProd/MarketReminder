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

namespace App\Helper\Interfaces;

use Google\Cloud\Storage\StorageObject;

/**
 * Interface CloudStoragePersisterHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudStoragePersisterHelperInterface
{
    /**
     * Allow to persist a new file into a given bucket.
     *
     * @param string    $bucketName
     * @param string    $fileName
     * @param array     $options
     *
     * @return StorageObject
     */
    public function persist(string $bucketName, string $fileName, $options = []): StorageObject;
}
