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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationBackupWriterIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBackupWriterIntegrationTest extends KernelTestCase
{
    /**
     * @var BackupConnectorInterface
     */
    private $backupConnector;

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
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testsItRefuseTheBackUpWithWrongFileSystemBackUpConnector(
        string $channel,
        string $locale,
        array $values
    ) {
        static::expectException(\LogicException::class);

        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector = new FileSystemConnector('test');
        $this->backupConnector->setBackup(false);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $locale, $channel.'.'.$locale.'.yaml', $values);
        $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testsItRefuseTheBackUpWithWrongRedisBackUpConnector(
        string $channel,
        string $locale,
        array $values
    ) {
        static::expectException(\LogicException::class);

        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            'backup'.'_'.static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector->setBackup(false);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $locale, $channel.'.'.$locale.'.yaml', $values);
        $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testItRefuseToBackupSameContentWithFileSystemCacheAndFileSystemBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->connector = new FileSystemConnector('test');
        $this->backupConnector = new FileSystemConnector('backup_test');
        $this->backupConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $channel, $channel.'.'.$locale.'.yaml', $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testItRefuseToBackupSameContentWithFileSystemCacheAndRedisBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->connector = new FileSystemConnector('test');
        $this->backupConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            'backup'.'_'.static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $channel, $channel.'.'.$locale.'.yaml', $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testItRefuseToBackupSameContentWithRedisCacheAndFileSystemBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector = new FileSystemConnector('test');
        $this->backupConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $channel, $channel.'.'.$locale.'.yaml', $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testItRefuseToBackupSameContentWithRedisCacheAndRedisBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            'backup'.'_'.static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $channel, $channel.'.'.$locale.'.yaml', $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testItAcceptToBackupContentWithFileSystemCacheAndFileSystemBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->connector = new FileSystemConnector('test');
        $this->backupConnector = new FileSystemConnector('backup_test');
        $this->backupConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $locale, $channel.'.'.$locale.'.yaml', $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testItAcceptToBackupContentWithFileSystemCacheAndRedisBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->connector = new FileSystemConnector('test');
        $this->backupConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            'backup'.'_'.static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $locale, $channel.'.'.$locale.'.yaml', $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testItAcceptToBackupContentWithRedisCacheAndFileSystemBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector = new FileSystemConnector('test');
        $this->backupConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $locale, $channel.'.'.$locale.'.yaml', $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     */
    public function testItAcceptToBackupContentWithRedisCacheAndRedisBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->connector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector = new RedisConnector(
            static::$container->getParameter('redis.test_dsn'),
            'backup'.'_'.static::$container->getParameter('redis.namespace_test')
        );
        $this->backupConnector->setBackup(true);

        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backupConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);

        $this->backupConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();

        $this->cloudTranslationWriter->write($locale, $locale, $channel.'.'.$locale.'.yaml', $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertTrue($processStatus);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('messages', 'fr', ['home.text' => 'Hello World !']);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->backupConnector = null;
        $this->connector = null;
    }
}
