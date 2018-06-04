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
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ConnectorTestCase.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class ConnectorTestCase extends KernelTestCase
{
    /**
     * @var ConnectorInterface
     */
    protected $backUpConnector;

    /**
     * @var CloudTranslationBackupWriterInterface
     */
    protected $cloudTranslationBackupWriter;

    /**
     * @var CloudTranslationWriterInterface
     */
    protected $cloudTranslationWriter;

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

    protected function createFileSystemConnector()
    {
        $this->connector = new FileSystemConnector('test');

        $this->cloudTranslationWriter = new CloudTranslationWriter($this->connector);

        $this->connector->getAdapter()->clear();
    }

    protected function createRedisConnector()
    {
        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );

        $this->cloudTranslationWriter = new CloudTranslationWriter($this->connector);

        $this->connector->getAdapter()->clear();
    }

    protected function createFileSystemBackUp()
    {
        $this->backUpConnector = new FileSystemConnector('backup_test');

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->getAdapter()->clear();
    }

    protected function createRedisBackUp()
    {
        $this->backUpConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test').'_backup'
        );

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->getAdapter()->clear();
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
