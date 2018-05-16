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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationWriterIntegrationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWriterIntegrationTest extends KernelTestCase
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

        // Used to clear the cache before any test (if not, the cache will return always the same values).
        $this->redisConnector->getAdapter()->clear();
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToStoreWithSameContent(string $locale, string $channel, string $fileName, array $values)
    {
        $this->redisTranslationWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItSaveEntries(string $locale, string $channel, string $fileName, array $values)
    {
        $processStatus = $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItUpdateAndSaveItem(string $locale, string $channel, string $fileName, array $values)
    {
        $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        $processStatus = $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            ['user.creation_success' => 'Hello user !']
        );

        static::assertTrue($processStatus);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'Inventory management']);
        yield array('fr', 'validators', 'validators.fr.yaml', ['reset_password.title.text' => 'Réinitialiser votre mot de passe.']);
    }
}
