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

namespace App\Tests\Infra\Redis\Translation;

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
 * Class CloudTranslationWriterIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWriterIntegrationTest extends KernelTestCase
{
    /**
     * @var CloudTranslationBackupWriterInterface
     */
    private $cloudTranslationBackupWriter;

    /**
     * @var BackupConnectorInterface
     */
    private $backUpConnector;

    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $cloudTranslationWriter;

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
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToStoreSameContentWithFileSystemCacheAndFileSystemBackup(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createFileSystemCacheAndFileSystemBackUp();

        $fileSystemWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);
        $fileSystemWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $fileSystemWriter->write($locale, $channel, $fileName, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToStoreSameContentWithFileSystemCacheAndRedisBackup(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createFileSystemCacheAndRedisBackUp();

        $fileSystemWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);
        $fileSystemWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $fileSystemWriter->write($locale, $channel, $fileName, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToStoreSameContentWithRedisCacheAndRedisBackup(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createRedisCacheAndRedisBackUp();

        $redisWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);
        $redisWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $redisWriter->write($locale, $channel, $fileName, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToStoreSameContentWithRedisCacheAndFileSystemBackup(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createRedisCacheAndFileSystemBackUp();

        $redisWriter = new CloudTranslationWriter($this->cloudTranslationBackupWriter, $this->connector);
        $redisWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $redisWriter->write($locale, $channel, $fileName, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItSaveEntriesWithFileSystemCacheAndFileSystemBackup(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createFileSystemCacheAndFileSystemBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );

        $processStatus = $this->cloudTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItSaveEntriesWithRedisCacheAndRedisBackup(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createRedisCacheAndRedisBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );

        $processStatus = $this->cloudTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItUpdateAndSaveItemWithFileSystemCacheAndFileSystemBackup(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createFileSystemCacheAndFileSystemBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );

        $this->cloudTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        $processStatus = $this->cloudTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            ['user.creation_success' => 'Hello user !']
        );

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItUpdateAndSaveItemWithRedisCacheAndRedisBackup(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createRedisCacheAndRedisBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );

        $this->cloudTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        $processStatus = $this->cloudTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            ['user.creation_success' => 'Hello user !']
        );

        static::assertTrue($processStatus);
    }

    /**
     * Create a Filesystem Cache & Filesystem backup.
     */
    private function createFileSystemCacheAndFileSystemBackUp()
    {
        $this->backUpConnector = new FileSystemConnector('backup_test');
        $this->connector = new FileSystemConnector('test');
        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * Create a Filesystem Cache & Redis backup.
     */
    private function createFileSystemCacheAndRedisBackUp()
    {
        $this->backUpConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test').'_backup'
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
    private function createRedisCacheAndFileSystemBackUp()
    {
        $this->backUpConnector = new FileSystemConnector('backup_test');
        $this->connector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );
        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * Create a Redis Cache & Redis backup.
     */
    private function createRedisCacheAndRedisBackUp()
    {
        $this->backUpConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test').'_backup'
        );
        $this->connector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );
        $this->cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->backUpConnector);

        $this->backUpConnector->setBackup(true);

        $this->backUpConnector->getAdapter()->clear();
        $this->connector->getAdapter()->clear();
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'Inventory management']);
        yield array('fr', 'validators', 'validators.fr.yaml', ['reset_password.title.text' => 'Réinitialiser votre mot de passe.']);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->backUpConnector = null;
        $this->connector = null;
    }
}
