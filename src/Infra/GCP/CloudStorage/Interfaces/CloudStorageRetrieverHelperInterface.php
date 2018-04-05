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
use Psr\Http\Message\StreamInterface;

/**
 * Interface CloudStorageRetrieverHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudStorageRetrieverHelperInterface
{
    /**
     * CloudStorageRetrieverHelperInterface constructor.
     *
     * @param CloudStorageBridgeInterface $cloudStorageBridge
     */
    public function __construct(CloudStorageBridgeInterface $cloudStorageBridge);

    /**
     * Allow to check if a file exist before calling the retrieving methods.
     *
     * @param string $bucketName    The name of the bucket.
     * @param string $fileName      The name of the file.
     *
     * @return bool                 Whether or not the file exist.
     */
    public function checkFileExistence(string $bucketName, string $fileName): bool;

    /**
     * Allow to retrieve a file as a physical file using StreamInterface.
     *
     * @param string $bucketName    The name of the Bucket
     * @param string $fileName      The name of the file
     * @param string $filePath      The path of the file
     *
     * @return StreamInterface      The file as stream
     */
    public function retrieveAsFile(string $bucketName, string $fileName, string $filePath): StreamInterface;

    /**
     * Allow to retrieve a file as a string.
     *
     * @param string $bucketName    The name of the bucket to fetch.
     * @param string $fileName      The name of the file.
     *
     * @return string               The public url of the file.
     */
    public function retrieveAsString(string $bucketName, string $fileName): string;
}
