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

use App\Tests\TestCase\CloudTranslationTestCase;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;

/**
 * Class CloudTranslationBackupWriterSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBackupWriterSystemTest extends CloudTranslationTestCase
{
    use TestCaseTrait;

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToBackupSameContentWithFileSystemBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert(
            'main.peak_memory < 50kB',
            'CloudTranslationBackUp no store Filesystem memory usage'
        );
        $configuration->assert(
            'main.network_in == 0B',
            'CloudTranslationBackUp no store Filesystem network in'
        );
        $configuration->assert(
            'main.network_out == 0B',
            'CloudTranslationBackUp no store Filesystem network out'
        );

        $this->createFileSystemBackUp();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationBackUpWriter->warmBackUp(
            'messages',
            'fr',
            ['home.text' => 'Hello World !']
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationBackUpWriter->warmBackUp(
                'messages',
                'fr',
                ['home.text' => 'Hello World !']
            );
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToBackupSameContentWithRedisBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert(
            'main.peak_memory < 80kB',
            'CloudTranslationBackUp no store Redis memory usage'
        );
        $configuration->assert(
            'main.network_in < 390B',
            'CloudTranslationBackUp no store Redis network in'
        );
        $configuration->assert(
            'main.network_out < 180B',
            'CloudTranslationBackUp no store Redis network out'
        );

        $this->createRedisBackUp();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationBackUpWriter->warmBackUp(
            'messages',
            'fr',
            ['home.text' => 'Hello World !']
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationBackUpWriter->warmBackUp(
                'messages',
                'fr',
                ['home.text' => 'Hello World !']
            );
        });
    }
}
