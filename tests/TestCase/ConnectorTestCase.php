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

namespace App\Tests\TestCase;

use App\Infra\GCP\CloudTranslation\CloudTranslationBackupWriter;
use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\BackupConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ConnectorTestCase.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class ConnectorTestCase extends KernelTestCase
{
    /**
     * @var BackupConnectorInterface
     */
    protected $backUpConnector;

    /**
     * @var CloudTranslationBackupWriterInterface
     */
    protected $cloudTranslationBackupWriter;

    /**
     * @var ConnectorInterface
     */
    protected $connector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();
    }

    /**
     * Create a Filesystem Cache & Filesystem backup via a Mock.
     *
     * @throws \ReflectionException
     */
    protected function createFileSystemCacheAndFileSystemBackUpMockWithGoodProcess()
    {
        $this->backUpConnector = new FileSystemConnector('backup_test');
        $this->connector = new FileSystemConnector('test');
        $this->cloudTranslationBackupWriter = $this->createMock(CloudTranslationBackupWriterInterface::class);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * Create a Filesystem Cache & Filesystem backup.
     */
    protected function createFileSystemCacheAndFileSystemBackUp()
    {
        $this->backUpConnector = new FileSystemConnector('backup_test');
        $this->connector = new FileSystemConnector('test');
        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * Create a Filesystem Cache & Redis backup via a Mock.
     *
     * @throws \ReflectionException
     */
    protected function createFileSystemCacheAndRedisBackUpMockWithGoodProcess()
    {
        $this->backUpConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test').'_backup'
        );
        $this->connector = new FileSystemConnector('test');
        $this->cloudTranslationBackupWriter = $this->createMock(CloudTranslationBackupWriterInterface::class);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * Create a Filesystem Cache & Redis backup.
     */
    protected function createFileSystemCacheAndRedisBackUp()
    {
        $this->backUpConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test').'_backup'
        );
        $this->connector = new FileSystemConnector('test');
        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * Create a Redis Cache & Filesystem backup.
     */
    protected function createRedisCacheAndFileSystemBackUp()
    {
        $this->backUpConnector = new FileSystemConnector('backup_test');
        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * Create a Redis Cache & Redis backup.
     */
    protected function createRedisCacheAndRedisBackUp()
    {
        $this->backUpConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test').'_backup'
        );
        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->backUpConnector = null;
        $this->connector = null;
    }
}
