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

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationWarmerIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWarmerIntegrationTest extends KernelTestCase
{
    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->cloudTranslationWarmer = static::$container->get(CloudTranslationWarmerInterface::class);

        static::$container->get(ConnectorInterface::class)->getAdapter()->clear();
    }

    /**
     * @dataProvider provideWrongLocale
     *
     * @param string $channel
     * @param string $locale
     */
    public function testWrongLocale(string $channel, string $locale)
    {
        static::expectException(\InvalidArgumentException::class);

        $this->cloudTranslationWarmer->warmTranslationsCache($channel, $locale);
    }

    /**
     * @dataProvider provideWrongChannel
     *
     * @param string $channel
     * @param string $locale
     */
    public function testWrongChannel(
        string $channel,
        string $locale
    ) {
        static::expectException(\InvalidArgumentException::class);

        $this->cloudTranslationWarmer->warmTranslationsCache($channel, $locale);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $channel
     * @param string $locale
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testCacheIsValid(
        string $channel,
        string $locale
    ) {
        $this->cloudTranslationWarmer->warmTranslationsCache($channel, $locale);

        $cacheEntry = static::$container->get(CloudTranslationRepositoryInterface::class)->getEntries($channel.'.'.$locale.'.yaml');

        static::assertNotNull($cacheEntry);
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
