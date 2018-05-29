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

use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\CloudTranslationHelper;
use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationHelperInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationWarmerSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationWarmerSystemTest extends KernelTestCase
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
     * @var CloudTranslationHelperInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $redisTranslationWarmer;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $redisTranslationWriter;

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

        $this->acceptedLocales = static::$kernel->getContainer()->getParameter('accepted_locales');
        $this->acceptedChannels = static::$kernel->getContainer()->getParameter('accepted_channels');
        $this->translationsFolder = static::$kernel->getContainer()->getParameter('translator.default_path');

        $cloudTranslationBridge = new CloudTranslationBridge(
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials.filename'),
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials')
        );

        $redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->cloudTranslationWarmer = new CloudTranslationHelper($cloudTranslationBridge);
        $this->redisTranslationRepository = new CloudTranslationRepository($redisConnector);
        $this->redisTranslationWriter = new CloudTranslationWriter($redisConnector);

        $this->redisTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
            $this->translationsFolder
        );

        $redisConnector->getAdapter()->clear();
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testWrongChannelIsUsed()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 14kB', 'Translation warm wrong channel memory usage');
        $configuration->assert('main.network_in == 0B', 'Translation warm wrong channel network call');
        $configuration->assert('main.network_out == 0B', 'Translation warm wrong channel network callees');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation warm wrong channel HTTP request.');

        $this->redisTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {

            $this->expectException(\InvalidArgumentException::class);

            $this->redisTranslationWarmer->warmTranslations('toto', 'it');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testWrongLocaleIsUsed()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 15kB', 'Translation warm wrong locale memory usage');
        $configuration->assert('main.network_in == 0B', 'Translation warm wrong locale network call');
        $configuration->assert('main.network_out == 0B', 'Translation warm wrong locale network callees');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation warm wrong locale HTTP request.');

        $this->redisTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {

            $this->expectException(\InvalidArgumentException::class);

            $this->redisTranslationWarmer->warmTranslations('messages', 'it');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testCacheIsValid()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 175kB', 'Translation warm no translation memory usage');
        $configuration->assert('main.network_in < 26kB', 'Translation warm no translation network call');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation warm no translation HTTP request.');

        $this->redisTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationWarmer->warmTranslations('messages', 'fr');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testCacheIsValidButTranslationIsCalled()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1.6MB', 'Translation warm translation memory usage');
        $configuration->assert('main.network_in < 25kB', 'Translation warm translation network call');
        $configuration->assert('metrics.http.requests.count <= 2', 'Translation warm translation maximum HTTP requests.');

        $this->redisTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationWarmer->warmTranslations('messages', 'en');
        });
    }
}
