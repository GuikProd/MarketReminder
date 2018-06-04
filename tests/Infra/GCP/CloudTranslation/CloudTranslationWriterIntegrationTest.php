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
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Tests\TestCase\ConnectorTestCase;

/**
 * Class CloudTranslationWriterIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWriterIntegrationTest extends ConnectorTestCase
{
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
        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();

        $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

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
        $this->createFileSystemConnector();
        $this->createRedisBackUp();

        $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

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
        $this->createRedisConnector();
        $this->createRedisBackUp();

        $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

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
        $this->createRedisConnector();
        $this->createFileSystemBackUp();

        $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

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
        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();

        $processStatus = $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

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
        $this->createRedisConnector();
        $this->createRedisBackUp();

        $processStatus = $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

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
        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();

        $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

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
        $this->createRedisConnector();
        $this->createRedisBackUp();

        $this->cloudTranslationWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $this->cloudTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            ['user.creation_success' => 'Hello user !']
        );

        static::assertTrue($processStatus);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'Inventory management']);
        yield array('fr', 'validators', 'validators.fr.yaml', ['reset_password.title.text' => 'Réinitialiser votre mot de passe.']);
    }
}
