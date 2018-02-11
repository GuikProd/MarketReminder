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

namespace App\Helper\Interfaces\CloudStorage;

/**
 * Interface CloudStorageCleanerHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudStorageCleanerHelperInterface
{
    /**
     * Allow to delete a object stored into a define bucket.
     *
     * @param string $bucketName    The name of the bucket.
     * @param string $fileName      The name of the file.
     */
    public function delete(string $bucketName, string $fileName): void;
}
