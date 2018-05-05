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
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RedisTranslationWriterIntegrationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWriterIntegrationTest extends KernelTestCase
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var array
     */
    private $goodTestingData = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->serializer = static::$kernel->getContainer()->get('serializer');

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        // Used to clear the cache before any test (if not, the cache will return always the same values).
        $this->redisConnector->getAdapter()->clear();

        $this->goodTestingData = [
            'home.text' => 'Inventory management',
            'reset_password.title.text' => 'Réinitialiser votre mot de passe.'
        ];
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToStoreWithSameContent()
    {
        $redisTranslationWriter = new RedisTranslationWriter(
            $this->serializer,
            $this->redisConnector
        );

        $redisTranslationWriter->write(
            'fr',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $processStatus = $redisTranslationWriter->write(
            'fr',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        static::assertFalse($processStatus);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToSaveWithSameFileName()
    {
        $redisTranslationWriter = new RedisTranslationWriter(
            $this->serializer,
            $this->redisConnector
        );

        $redisTranslationWriter->write(
            'fr',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $processStatus = $redisTranslationWriter->write(
            'fr',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        static::assertFalse($processStatus);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItSaveEntries()
    {
        $redisTranslationWriter = new RedisTranslationWriter(
            $this->serializer,
            $this->redisConnector
        );

        $processStatus = $redisTranslationWriter->write(
            'fr',
            'validators.fr.yaml',
            $this->goodTestingData
        );

        static::assertTrue($processStatus);
    }
}
