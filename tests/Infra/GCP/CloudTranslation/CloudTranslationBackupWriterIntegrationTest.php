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

use App\Tests\TestCase\ConnectorTestCase;

/**
 * Class CloudTranslationBackupWriterIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBackupWriterIntegrationTest extends ConnectorTestCase
{
    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToBackupSameContentWithFileSystemBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->createFileSystemBackUp();

        $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToBackupSameContentWithRedisBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->createRedisBackUp();

        $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItAcceptToBackupContentWithFileSystemBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->createFileSystemBackUp();

        $processStatus = $this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values);

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItAcceptToBackupContentWithRedisBackUp(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->createRedisBackUp();

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
}
