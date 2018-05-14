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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslation;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class RedisTranslationWriterUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWriterUnitTest extends TestCase
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
        $this->redisConnector = $this->getMockBuilder(RedisConnectorInterface::class)
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->redisConnector->method('getAdapter')->willReturn($this->adapter);
    }

    public function testItImplements()
    {
        $redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        static::assertInstanceOf(
            RedisTranslationWriterInterface::class,
            $redisTranslationWriter
        );
    }

    /**
     * @dataProvider provideRightData
     *
     * @param $locale
     * @param $channel
     * @param $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItStopIfTranslationExist($locale, $channel, $fileName, array $values)
    {
        $translations = [];

        foreach ($values as $item => $value) {
            $translations[] = new RedisTranslation([
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

        $redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $processStatus = $redisTranslationWriter->write($locale, $channel, $fileName, $values);

        static::assertFalse($processStatus);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'Bonjour le monde']);
    }
}
