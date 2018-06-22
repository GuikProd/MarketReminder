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

namespace App\Tests\Infra\GCP\CloudTranslation\Domain\Repository;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\CloudTranslationRepository;
use App\Tests\TestCase\CloudTranslationTestCase;

/**
 * Class CloudTranslationRepositoryIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationRepositoryIntegrationTest extends CloudTranslationTestCase
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
        $repository = $this->createRepository($this->connector);
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $entry = $repository->getEntries('validators.fr.yaml');

        static::assertNull($entry);
    }

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
    public function testItReturnNullWithFilesystem(
        string $locale,
        string $channel,
        string $filename,
        array $values
    ) {
        $this->createFileSystemConnector();
        $repository = $this->createRepository($this->connector);
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $entry = $repository->getEntries('validators.fr.yaml');

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
        $repository = $this->createRepository($this->connector);
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $entry = $repository->getEntries($filename);

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
    public function testItReturnAnEntryWithFilesystem(
        string $locale,
        string $channel,
        string $filename,
        array $values,
        string $key
    ) {
        $this->createFileSystemConnector();
        $repository = $this->createRepository($this->connector);
        $this->createCloudTranslationWriter();

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $entry = $repository->getEntries($filename);

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
        $this->createCloudTranslationWriter();
        $repository = $this->createRepository($this->connector);

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $entry = $repository->getSingleEntry($filename, $locale, $key);

        static::assertInstanceOf(CloudTranslationItemInterface::class, $entry);
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
    public function testItReturnASingleEntryWithFileSystem(
        string $locale,
        string $channel,
        string $filename,
        array $values,
        string $key
    ) {
        $this->createFileSystemConnector();
        $this->createCloudTranslationWriter();
        $repository = $this->createRepository($this->connector);

        $this->cloudTranslationWriter->write($locale, $channel, $filename, $values);

        $entry = $repository->getSingleEntry($filename, $locale, $key);

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

    /**
     * @param ConnectorInterface $connector
     *
     * @return CloudTranslationRepository
     */
    private function createRepository(ConnectorInterface $connector)
    {
        return new CloudTranslationRepository($connector);
    }
}
