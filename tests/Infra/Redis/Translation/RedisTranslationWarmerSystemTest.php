<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Infra\Redis\Translation;

use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\Infra\Redis\Translation\RedisTranslationWarmer;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationWarmerSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWarmerSystemTest extends KernelTestCase
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
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var RedisTranslationWarmerInterface
     */
    private $redisTranslationWarmer;

    /**
     * @var RedisTranslationWriterInterface
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

        $this->cloudTranslationWarmer = new CloudTranslationWarmer($cloudTranslationBridge);
        $this->redisTranslationRepository = new RedisTranslationRepository($redisConnector);
        $this->redisTranslationWriter = new RedisTranslationWriter($redisConnector);

        $this->redisTranslationWarmer = new RedisTranslationWarmer(
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
        $configuration->assert('main.peak_memory < 14kB', 'Translations warm wrong channel memory usage');
        $configuration->assert('main.network_in == 0B', 'Translations warm wrong channel network call');
        $configuration->assert('main.network_out == 0B', 'Translations warm wrong channel network callees');
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
        $configuration->assert('main.peak_memory < 15kB', 'Translations warm wrong locale memory usage');
        $configuration->assert('main.network_in == 0B', 'Translations warm wrong locale network call');
        $configuration->assert('main.network_out == 0B', 'Translations warm wrong locale network callees');
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
        $configuration->assert('main.peak_memory < 175kB', 'Translations warm no translation memory usage');
        $configuration->assert('main.network_in < 26kB', 'Translations warm no translation network call');
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
        $configuration->assert('main.peak_memory < 1.6MB', 'Translations warm translation memory usage');
        $configuration->assert('main.network_in < 25kB', 'Translations warm translation network call');
        $configuration->assert('metrics.http.requests.count <= 2', 'Translation warm translation maximum HTTP requests.');

        $this->redisTranslationWarmer->warmTranslations('messages', 'fr');

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationWarmer->warmTranslations('messages', 'en');
        });
    }
}
