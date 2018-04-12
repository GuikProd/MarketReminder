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

namespace App\Infra\GCP\Bridge;

use App\Infra\GCP\Bridge\Interfaces\CloudBridgeInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
use Google\Cloud\Storage\StorageClient;

/**
 * Class CloudStorageBridge.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageBridge implements CloudBridgeInterface, CloudStorageBridgeInterface
{
    /**
     * @var string
     */
    private $storageCredentialsFileName;

    /**
     * @var string
     */
    private $storageCredentialsFolder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $storageCredentialsFileName,
        string $storageCredentialsFolder
    ) {
        $this->storageCredentialsFileName = $storageCredentialsFileName;
        $this->storageCredentialsFolder = $storageCredentialsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageClient(): StorageClient
    {
        return new StorageClient([
            'keyFile' => json_decode(file_get_contents($this->storageCredentialsFolder.'/'.$this->storageCredentialsFileName), true)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function closeConnexion(): void
    {
        $this->storageCredentialsFileName = null;
        $this->storageCredentialsFolder = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): array
    {
        return [
            'keyFileName' => $this->storageCredentialsFileName,
            'keyFilePath' => $this->storageCredentialsFolder
        ];
    }
}
