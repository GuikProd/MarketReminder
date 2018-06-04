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

use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationItemInterface;
use App\Tests\TestCase\ConnectorTestCase;

/**
 * Class CloudTranslationRepositoryIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationRepositoryIntegrationTest extends ConnectorTestCase
{
    /**
     * @dataProvider provideTranslationItem()
     *
     * @param string $locale
     * @param string $channel
     * @param string $filename
     * @param array  $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNullWithRedis(
        string $locale,
        string $channel,
        string $filename,
        array $values
    ) {
        $this->createRedisConnector();

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $redisTranslationRepository = new CloudTranslationRepository($this->connector);

        $entry = $redisTranslationRepository->getEntries('validators.fr.yaml');

        static::assertNull($entry);
    }

    /**
     * @dataProvider provideTranslationItems
     *
     * @param string $locale
     * @param string $channel
     * @param string $filename
     * @param array  $values
     * @param string $key
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnAnEntryWithRedis(
        string $locale,
        string $channel,
        string $filename,
        array $values,
        string $key
    ) {
        $this->createRedisConnector();

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $redisTranslationReader = new CloudTranslationRepository($this->connector);

        $entry = $redisTranslationReader->getEntries($filename);

        static::assertCount(1, $entry);
        static::assertInstanceOf(CloudTranslationItemInterface::class, $entry[0]);
        static::assertSame($key, $entry[0]->getKey());
    }

    /**
     * @dataProvider provideTranslationItems
     *
     * @param string $locale
     * @param string $channel
     * @param string $filename
     * @param array  $values
     * @param string $key
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnASingleEntryWithRedis(
        string $locale,
        string $channel,
        string $filename,
        array $values,
        string $key
    ) {
        $this->createRedisConnector();

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $redisTranslationRepository = new CloudTranslationRepository($this->connector);

        $entry = $redisTranslationRepository->getSingleEntry($filename, $locale, $key);

        static::assertInstanceOf(CloudTranslationItemInterface::class, $entry);
    }

    /**
     * @return \Generator
     */
    public function provideTranslationItem()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'hello !']);
    }

    /**
     * @return \Generator
     */
    public function provideTranslationItems()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'hello !'], 'home.text');
    }
}
