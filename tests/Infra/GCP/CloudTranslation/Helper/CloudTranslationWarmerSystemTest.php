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

use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Client\CloudTranslationClient;
use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use App\Tests\TestCase\CloudTranslationTestCase;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;

/**
 * Class CloudTranslationWarmerSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWarmerSystemTest extends CloudTranslationTestCase
{
    use TestCaseTrait;

    /**
     * @var string
     */
    private $acceptedChannels;

    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * @var CloudTranslationClientInterface
     */
    private $cloudTranslationHelper;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var string
     */
    private $translationsFolder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->acceptedLocales = static::$container->getParameter('accepted_locales');
        $this->acceptedChannels = static::$container->getParameter('accepted_channels');
        $this->translationsFolder = static::$container->getParameter('translator.default_path');

        $cloudTranslationBridge = new CloudTranslationBridge(
            static::$container->getParameter('cloud.translation_credentials.filename'),
            static::$container->getParameter('cloud.translation_credentials')
        );

        $this->cloudTranslationHelper = new CloudTranslationClient($cloudTranslationBridge);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testWrongChannelIsUsedWithFileSystemConnectorAndFileSystemBackup()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 14kB', 'Translation warm wrong channel memory usage');
        $configuration->assert('main.network_in == 0B', 'Translation warm wrong channel network in');
        $configuration->assert('main.network_out == 0B', 'Translation warm wrong channel network out');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation warm wrong channel HTTP request.');

        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();
        $this->createCloudTranslationWriter();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $this->cloudTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->expectException(\InvalidArgumentException::class);

            $this->cloudTranslationWarmer->warmTranslations('toto', 'it');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testWrongLocaleIsUsedWithRedisCacheAndRedisBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 15kB', 'Translation warm wrong locale memory usage');
        $configuration->assert('main.network_in == 0B', 'Translation warm wrong locale network in');
        $configuration->assert('main.network_out == 0B', 'Translation warm wrong locale network out');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation warm wrong locale HTTP request.');

        $this->createRedisConnector();
        $this->createRedisBackUp();
        $this->createCloudTranslationWriter();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $this->cloudTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->expectException(\InvalidArgumentException::class);

            $this->cloudTranslationWarmer->warmTranslations('messages', 'it');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCacheIsValidWithFileSystemCacheAndFileSystemBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 175kB', 'Translation warm no translation memory usage');
        $configuration->assert('main.network_in < 26kB', 'Translation warm no translation network in');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation warm no translation HTTP request.');

        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();
        $this->createCloudTranslationWriter();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $this->cloudTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationWarmer->warmTranslations('messages', 'fr');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCacheIsValidWithRedisCacheAndRedisBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 175kB', 'Translation warm no translation memory usage');
        $configuration->assert('main.network_in < 26kB', 'Translation warm no translation network in');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation warm no translation HTTP request.');

        $this->createRedisConnector();
        $this->createRedisBackUp();
        $this->createCloudTranslationWriter();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $this->cloudTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationWarmer->warmTranslations('messages', 'fr');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCacheIsValidButTranslationIsCalledWithFileSystemCacheAndFileSystemBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1.6MB', 'Translation warm translation memory usage');
        $configuration->assert('main.network_in < 25kB', 'Translation warm translation network call');
        $configuration->assert('metrics.http.requests.count <= 2', 'Translation warm translation maximum HTTP requests.');

        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();
        $this->createCloudTranslationWriter();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $this->cloudTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationWarmer->warmTranslations('messages', 'en');
        });
    }


    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCacheIsValidButTranslationIsCalledWithRedisCacheAndRedisBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1.6MB', 'Translation warm translation memory usage');
        $configuration->assert('main.network_in < 25kB', 'Translation warm translation network call');
        $configuration->assert('metrics.http.requests.count <= 2', 'Translation warm translation maximum HTTP requests.');

        $this->createRedisConnector();
        $this->createRedisBackUp();
        $this->createCloudTranslationWriter();
        $this->createCloudTranslationBackUpWriter();

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $this->cloudTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationWarmer->warmTranslations('messages', 'en');
        });
    }
}
