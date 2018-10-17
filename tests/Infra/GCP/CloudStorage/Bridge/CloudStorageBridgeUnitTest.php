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

namespace App\Tests\Infra\GCP\CloudStorage\Bridge;

use App\Infra\GCP\CloudStorage\Bridge\CloudStorageBridge;
use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\Loader\AbstractCredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use Google\Cloud\Storage\StorageClient;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudStorageBridgeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudStorageBridgeUnitTest extends TestCase
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
     * @var LoaderInterface
     */
    private $credentialsLoader;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->bucketCredentialsFolder = __DIR__.'/../../../../_credentials';
        $this->bucketCredentialsFileName = 'credentials.json';
        $this->credentialsLoader = new AbstractCredentialsLoader();
    }

    public function testItImplements()
    {
        $cloudStorage = new CloudStorageBridge(
            $this->bucketCredentialsFileName,
            $this->bucketCredentialsFolder,
            $this->credentialsLoader
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
            $this->bucketCredentialsFolder,
            $this->credentialsLoader
        );

        static::assertInstanceOf(
            StorageClient::class,
            $cloudStorage->getStorageClient()
        );
    }
}
