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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNull()
    {
        $this->redisTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $redisTranslationReader = new CloudTranslationRepository($this->redisConnector);

        $entry = $redisTranslationReader->getEntries('validators.fr.yaml');

        static::assertNull($entry);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnAnEntry()
    {
        $this->redisTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $redisTranslationReader = new CloudTranslationRepository($this->redisConnector);

        $entry = $redisTranslationReader->getEntries('messages.fr.yaml');

        static::assertCount(1, $entry);
        static::assertInstanceOf(CloudTranslationItemInterface::class, $entry[0]);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnASingleEntry()
    {
        $this->redisTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $redisTranslationRepository = new CloudTranslationRepository($this->redisConnector);

        $entry = $redisTranslationRepository->getSingleEntry(
            'messages.fr.yaml',
            'fr',
            'home.text'
        );

        static::assertInstanceOf(CloudTranslationItemInterface::class, $entry);
    }
}
