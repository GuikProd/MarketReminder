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

use App\Infra\GCP\CloudTranslation\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ApcuConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationWriterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class CloudTranslationWriterUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationWriterUnitTest extends TestCase
{
    /**
     * @var TagAwareAdapterInterface
     */
    private $adapter;

    /**
     * @var CacheItemInterface
     */
    private $cacheItem;

    /**
     * @var ApcuConnectorInterface
     */
    private $apcuConnector;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->adapter = $this->createMock(TagAwareAdapterInterface::class);
        $this->cacheItem = $this->createMock(CacheItemInterface::class);
        $this->apcuConnector = $this->createMock(ApcuConnectorInterface::class);
        $this->redisConnector = $this->createMock(RedisConnectorInterface::class);

        $this->apcuConnector->method('getAdapter')->willReturn($this->adapter);
        $this->redisConnector->method('getAdapter')->willReturn($this->adapter);
    }

    public function testItImplementsWithApcuConnector()
    {
        $redisTranslationWriter = new CloudTranslationWriter($this->apcuConnector);

        static::assertInstanceOf(
            CloudTranslationWriterInterface::class,
            $redisTranslationWriter
        );
    }

    public function testItImplementsWithRedisConnector()
    {
        $redisTranslationWriter = new CloudTranslationWriter($this->redisConnector);

        static::assertInstanceOf(
            CloudTranslationWriterInterface::class,
            $redisTranslationWriter
        );
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array  $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItStopIfTranslationExistAndIsValid(string $locale, string $channel, string $fileName, array $values)
    {
        $translations = [];

        foreach ($values as $item => $value) {
            $translations[] = new CloudTranslationItem([
                '_locale' => $locale,
                'channel' => $channel,
                'tag' => Uuid::uuid4()->toString(),
                'key' => $item,
                'value' => $value
            ]);
        }

        $this->adapter->method('getItem')->willReturn($this->cacheItem);
        $this->cacheItem->method('isHit')->willReturn(true);
        $this->cacheItem->method('get')->willReturn($translations);

        $redisTranslationWriter = new CloudTranslationWriter($this->redisConnector);

        $processStatus = $redisTranslationWriter->write($locale, $channel, $fileName, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'Bonjour le monde']);
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.content' => 'Bienvenue sur le contenu.']);
    }
}
