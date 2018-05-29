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

use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationRepositoryIntegrationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationRepositoryIntegrationTest extends KernelTestCase
{
    /**
     * @var CloudTranslationWriterInterface
     */
    private $apcuTranslationWriter;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $redisTranslationWriter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->redisTranslationWriter = new CloudTranslationWriter($this->redisConnector);

        $this->redisConnector->getAdapter()->clear();
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
        $this->redisTranslationWriter->write($locale, $channel, $filename, $values);

        $redisTranslationRepository = new CloudTranslationRepository($this->redisConnector);

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
        $this->redisTranslationWriter->write($locale, $channel, $filename, $values);

        $redisTranslationReader = new CloudTranslationRepository($this->redisConnector);

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
        $this->redisTranslationWriter->write($locale, $channel, $filename, $values);

        $redisTranslationRepository = new CloudTranslationRepository($this->redisConnector);

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
        $this->redisConnector = null;
    }
}
