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

namespace App\Tests\Infra\GCP\CloudTranslation\Helper;

use App\Tests\TestCase\CloudTranslationTestCase;

/**
 * Class CloudTranslationBackupWriterIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBackupWriterIntegrationTest extends CloudTranslationTestCase
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
    public function testItRefuseToBackupSameContent(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->createFileSystemBackUp();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationBackUpWriter->warmBackUp($channel, $locale, $values);

        $processStatus = $this->cloudTranslationBackUpWriter->warmBackUp($channel, $locale, $values);

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
    public function testItAcceptToBackupContent(
        string $channel,
        string $locale,
        array $values
    ) {
        $this->createFileSystemBackUp();
        $this->createCloudTranslationBackUpWriter();

        $processStatus = $this->cloudTranslationBackUpWriter->warmBackUp($channel, $locale, $values);

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
