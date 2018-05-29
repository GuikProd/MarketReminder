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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationWarmerIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationWarmerIntegrationTest extends KernelTestCase
{
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
     * @dataProvider provideWrongChannel
     *
     * @param string $channel
     * @param string $locale
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testWrongChannel(string $channel, string $locale)
    {
        $this->expectException(\InvalidArgumentException::class);

        $processStatus = $this->redisTranslationWarmer->warmTranslations($channel, $locale);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideWrongLocale
     *
     * @param string $channel
     * @param string $locale
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testWrongLocale(string $channel, string $locale)
    {
        $this->expectException(\InvalidArgumentException::class);

        $processStatus = $this->redisTranslationWarmer->warmTranslations($channel, $locale);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testCacheIsValid(string $channel, string $locale)
    {
        $processStatus = $this->redisTranslationWarmer->warmTranslations($channel, $locale);

        static::assertTrue($processStatus);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('messages', 'fr');
        yield array('validators', 'fr');
        yield array('messages', 'en');
        yield array('validators', 'en');
    }

    /**
     * @return \Generator
     */
    public function provideWrongChannel()
    {
        yield array('toto', 'fr');
        yield array('titi', 'fr');
    }

    /**
     * @return \Generator
     */
    public function provideWrongLocale()
    {
        yield array('messages', 'it');
        yield array('validators', 'ru');
    }
}
