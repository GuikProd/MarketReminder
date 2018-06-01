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

use App\Infra\GCP\CloudTranslation\CloudTranslationBackupWriter;
use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\BackupConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationRepositoryIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationRepositoryIntegrationTest extends KernelTestCase
{
    /**
     * @var BackupConnectorInterface
     */
    private $backUpConnector;

    /**
     * @var RedisConnectorInterface
     */
    private $connector;

    /**
     * @var CloudTranslationBackupWriterInterface
     */
    private $cloudTranslationBackUpWriter;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $cloudTranslationWriter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->connector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );
        $this->backUpConnector = new FileSystemConnector('test');
        $this->backUpConnector->setBackup(true);

        $this->cloudTranslationBackUpWriter = new CloudTranslationBackupWriter($this->backUpConnector);
        $this->cloudTranslationWriter = new CloudTranslationWriter($this->cloudTranslationBackUpWriter, $this->connector);

        $this->connector->getAdapter()->clear();
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
    public function testItReturnNullWithRedis(
        string $locale,
        string $channel,
        string $filename,
        array $values
    ) {
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

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->connector = null;
    }
}
