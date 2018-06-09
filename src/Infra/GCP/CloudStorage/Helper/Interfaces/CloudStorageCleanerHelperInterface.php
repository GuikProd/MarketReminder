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

/**
 * Interface CloudStorageCleanerHelperInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudStorageCleanerHelperInterface
{
    /**
     * CloudStorageCleanerHelperInterface constructor.
     *
     * @param CloudStorageBridgeInterface $cloudStorageBridgeInterface
     */
    public function __construct(CloudStorageBridgeInterface $cloudStorageBridgeInterface);

    /**
     * Allow to delete a object stored into a define bucket.
     *
     * @param string $bucketName    The name of the bucket.
     * @param string $fileName      The name of the file.
     */
    public function delete(string $bucketName, string $fileName): void;
}
