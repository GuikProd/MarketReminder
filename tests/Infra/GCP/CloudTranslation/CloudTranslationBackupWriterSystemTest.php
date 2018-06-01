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

namespace App\Tests\Infra\GCP\CloudTranslation;

use App\Infra\GCP\CloudTranslation\CloudTranslationBackupWriter;
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\BackupConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationBackupWriterSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBackupWriterSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var BackupConnectorInterface
     */
    private $backUpConnector;

    /**
     * @var CloudTranslationBackupWriterInterface
     */
    private $cloudTranslationBackupWriter;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $cloudTranslationWriter;

    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToBackupSameContentWithRedisCacheAndFileSystemBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 50kB', 'CloudTranslationBackUp no store memory usage');
        $configuration->assert('main.network_in == 0B', 'CloudTranslationBackUp no store network in');
        $configuration->assert('main.network_out == 0B', 'CloudTranslationBackUp no store network out');

        $this->createRedisCacheAndFileSystemBackUp();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'Hello World !']
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationBackupWriter->warmBackUp('messages', 'fr', ['home.text' => 'Hello World !']);
        });
    }

    /**
     * Allow to create a Redis cache & FileSystem backup.
     */
    private function createRedisCacheAndFileSystemBackUp()
    {
        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->backUpConnector = new FileSystemConnector('test');
        $this->backUpConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->connector->getAdapter()->clear();
        $this->backUpConnector->getAdapter()->clear();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->connector = null;
        $this->backUpConnector = null;
    }
}
