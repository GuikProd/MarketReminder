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

use App\Infra\GCP\CloudTranslation\Client\CloudTranslationClient;
use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use App\Tests\TestCase\CloudBridgeTrait;
use App\Tests\TestCase\ConnectorTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationWarmerIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWarmerIntegrationTest extends KernelTestCase
{
    use CloudBridgeTrait;
    use ConnectorTrait;

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


        $this->createTranslationBridge('credentials.json');

        $this->cloudTranslationHelper = new CloudTranslationClient($this->translationBridge);
    }

    /**
     * @dataProvider provideWrongChannel
     *
     * @param string $channel
     * @param string $locale
     */
    public function testWrongChannelWithFileSystemCache(
        string $channel,
        string $locale
    ) {
        static::expectException(\InvalidArgumentException::class);

        $this->createFileSystemConnector();
        $this->cloudTranslationRepository = new CloudTranslationRepository($this->connector);
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $processStatus = $this->cloudTranslationWarmer->warmTranslations($channel, $locale);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideWrongLocale
     *
     * @param string $channel
     * @param string $locale
     */
    public function testWrongLocaleWithRedisCache(
        string $channel,
        string $locale
    ) {
        static::expectException(\InvalidArgumentException::class);

        $this->createRedisConnector();
        $this->cloudTranslationRepository = new CloudTranslationRepository($this->connector);
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $processStatus = $this->cloudTranslationWarmer->warmTranslations($channel, $locale);

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     */
    public function testCacheIsValidWithFileSystem(
        string $channel,
        string $locale
    ) {
        $this->createFileSystemConnector();
        $this->cloudTranslationRepository = new CloudTranslationRepository($this->connector);
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $processStatus = $this->cloudTranslationWarmer->warmTranslations($channel, $locale);

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     */
    public function testCacheIsValidWithRedisCache(
        string $channel,
        string $locale
    ) {
        $this->createRedisConnector();
        $this->cloudTranslationRepository = new CloudTranslationRepository($this->connector);
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationHelper,
            $this->cloudTranslationRepository,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $processStatus = $this->cloudTranslationWarmer->warmTranslations($channel, $locale);

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
