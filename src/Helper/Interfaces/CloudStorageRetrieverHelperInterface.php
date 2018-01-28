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

use Psr\Http\Message\StreamInterface;

/**
 * Interface CloudStorageRetrieverHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudStorageRetrieverHelperInterface
{
    /**
     * Allow to retrieve a file as a physical file using StreamInterface.
     *
     * @param string $bucketName the name of the Bucket
     * @param string $fileName   the name of the file
     * @param string $filePath   the path of the file
     *
     * @return StreamInterface the file as stream
     */
    public function retrieve(string $bucketName, string $fileName, string $filePath): StreamInterface;
}
