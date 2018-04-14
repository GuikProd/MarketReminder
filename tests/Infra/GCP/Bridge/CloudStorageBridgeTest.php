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

namespace App\Tests\Infra\GCP\Bridge;

use App\Infra\GCP\Bridge\CloudStorageBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
use Google\Cloud\Storage\StorageClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudStorageBridgeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageBridgeTest extends KernelTestCase
{
    /**
     * @var string
     */
    private $bucketCredentialsFolder;

    /**
     * @var string
     */
    private $bucketCredentialsFileName;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->bucketCredentialsFolder = static::$kernel->getContainer()
                                                        ->getParameter('cloud.storage_credentials');

        $this->bucketCredentialsFileName = static::$kernel->getContainer()
                                                          ->getParameter('cloud.storage_credentials.filename');
    }

    public function testItImplements()
    {
        $cloudStorage = new CloudStorageBridge(
            $this->bucketCredentialsFileName,
            $this->bucketCredentialsFolder
        );

        static::assertInstanceOf(
            CloudStorageBridgeInterface::class,
            $cloudStorage
        );
    }

    public function testReturnServiceBuilder()
    {
        $cloudStorage = new CloudStorageBridge(
            $this->bucketCredentialsFileName,
            $this->bucketCredentialsFolder
        );

        static::assertInstanceOf(
            StorageClient::class,
            $cloudStorage->getStorageClient()
        );
    }

    public function testCredentialsAreLoaded()
    {
        $cloudStorage = new CloudStorageBridge(
            $this->bucketCredentialsFileName,
            $this->bucketCredentialsFolder
        );

        static::assertSame(
            $this->bucketCredentialsFolder,
            $cloudStorage->getCredentials()['keyFilePath']
        );
    }

    public function testConnexionIsDown()
    {
        $cloudStorage = new CloudStorageBridge(
            $this->bucketCredentialsFileName,
            $this->bucketCredentialsFolder
        );

        $cloudStorage->closeConnexion();

        static::assertNull(
            $cloudStorage->getCredentials()['keyFilePath']
        );
    }
}
