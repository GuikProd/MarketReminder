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

namespace App\Infra\GCP\CloudStorage\Bridge;

use App\Infra\GCP\Bridge\Interfaces\CloudBridgeInterface;
use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use Google\Cloud\Storage\StorageClient;

/**
 * Class CloudStorageBridge.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudStorageBridge implements CloudBridgeInterface, CloudStorageBridgeInterface
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
     * @var LoaderInterface
     */
    private $credentialsLoader;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $credentialsFilename,
        string $credentialsFolder,
        LoaderInterface $loader
    ) {
        $this->storageCredentialsFileName = $credentialsFilename;
        $this->storageCredentialsFolder = $credentialsFolder;
        $this->credentialsLoader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageClient(): StorageClient
    {
        $this->credentialsLoader->loadJson($this->storageCredentialsFileName, $this->storageCredentialsFolder);

        return new StorageClient([
            'keyFile' => $this->credentialsLoader->getCredentials()
        ]);
    }
}
