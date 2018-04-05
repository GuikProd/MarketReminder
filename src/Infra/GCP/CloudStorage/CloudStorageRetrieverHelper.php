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

namespace App\Infra\GCP\CloudStorage;

use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStorageRetrieverHelperInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class CloudStorageRetrieverHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageRetrieverHelper implements CloudStorageRetrieverHelperInterface
{
    /**
     * @var CloudStorageBridgeInterface
     */
    private $cloudStorageBridge;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudStorageBridgeInterface $cloudStorageBridge)
    {
        $this->cloudStorageBridge = $cloudStorageBridge;
    }

    /**
     * {@inheritdoc}
     */
    public function checkFileExistence(string $bucketName, string $fileName): bool
    {
        return $this->cloudStorageBridge
                    ->getServiceBuilder()
                    ->storage()
                    ->bucket($bucketName)
                    ->object($fileName)
                    ->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAsFile(string $bucketName, string $fileName, string $filePath): StreamInterface
    {
        return $this->cloudStorageBridge
                    ->getServiceBuilder()
                    ->storage()
                    ->bucket($bucketName)
                    ->object($fileName)
                    ->downloadToFile($filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAsString(string $bucketName, string $fileName): string
    {
        return $this->cloudStorageBridge
                    ->getServiceBuilder()
                    ->storage()
                    ->bucket($bucketName)
                    ->object($fileName)
                    ->downloadAsString();
    }
}
