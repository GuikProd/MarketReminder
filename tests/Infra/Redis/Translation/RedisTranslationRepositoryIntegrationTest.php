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

use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationRepositoryIntegrationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationRepositoryIntegrationTest extends KernelTestCase
{
    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var RedisTranslationWriterInterface
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

        $this->redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

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

        $redisTranslationReader = new RedisTranslationRepository($this->redisConnector);

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

        $redisTranslationReader = new RedisTranslationRepository($this->redisConnector);

        $entry = $redisTranslationReader->getEntries('messages.fr.yaml');

        static::assertCount(1, $entry);
        static::assertInstanceOf(RedisTranslationInterface::class, $entry[0]);
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

        $redisTranslationRepository = new RedisTranslationRepository($this->redisConnector);

        $entry = $redisTranslationRepository->getSingleEntry(
            'messages.fr.yaml',
            'fr',
            'home.text'
        );

        static::assertInstanceOf(RedisTranslationInterface::class, $entry);
    }
}
