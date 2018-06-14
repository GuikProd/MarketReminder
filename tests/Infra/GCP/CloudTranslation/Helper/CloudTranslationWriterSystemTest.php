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

use App\Tests\TestCase\CloudTranslationTestCase;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;

/**
 * Class CloudTranslationWriterSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationWriterSystemTest extends CloudTranslationTestCase
{
    use TestCaseTrait;

    /**
     * @var array
     */
    private $goodTestingData = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->goodTestingData = [
            'home.text' => 'Inventory management',
            'reset_password.title.text' => 'Réinitialiser votre mot de passe.'
        ];
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItDoesNotSaveSameContentTwiceWithFileSystem()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'CloudTranslationWriter no write memory Filesystem usage');
        $configuration->assert('main.network_in == 0B', 'CloudTranslationWriter no write network Filesystem call');

        $this->createFileSystemConnector();
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationWriter->write(
                'fr',
                'messages',
                'messages.fr.yaml',
                $this->goodTestingData
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
    public function testItDoesNotSaveSameContentTwiceWithRedis()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'CloudTranslationWriter no write memory redis usage');
        $configuration->assert('main.network_in < 710B', 'CloudTranslationWriter no write network redis call');

        $this->createRedisConnector();
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationWriter->write(
                'fr',
                'messages',
                'messages.fr.yaml',
                $this->goodTestingData
            );
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testWithCacheWriteAndFileSystemUsage()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 70kB', 'CloudTranslationWriter Filesystem usage memory usage');
        $configuration->assert('main.network_in == 0B', 'CloudTranslationWriter Filesystem usage network in');
        $configuration->assert('main.network_out == 0B', 'CloudTranslationWriter Filesystem usage network out');

        $this->createFileSystemConnector();
        $this->createCloudTranslationWriter();

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationWriter->write(
                'fr',
                'validators',
                'validators.fr.yaml',
                $this->goodTestingData
            );
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testWithCacheWriteAndRedisUsage()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 70kB', 'CloudTranslationWriter redis usage memory usage');
        $configuration->assert('main.network_in < 30B', 'CloudTranslationWriter redis usage network in');
        $configuration->assert('main.network_out < 1MB', 'CloudTranslationWriter redis usage network out');

        $this->createRedisConnector();
        $this->createCloudTranslationWriter();

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationWriter->write(
                'fr',
                'validators',
                'validators.fr.yaml',
                $this->goodTestingData
            );
        });
    }
}
