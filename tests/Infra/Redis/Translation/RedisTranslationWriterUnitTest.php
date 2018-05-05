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
use App\Infra\Redis\Translation\RedisTranslationWriter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RedisTranslationWriterUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWriterUnitTest extends TestCase
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
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->redisConnector = $this->getMockBuilder(RedisConnectorInterface::class)
                                     ->disableOriginalConstructor()
                                     ->getMock();
    }

    public function testItImplements()
    {
        $redisTranslationWriter = new RedisTranslationWriter(
            $this->serializer,
            $this->redisConnector
        );

        static::assertInstanceOf(
            RedisTranslationWriterInterface::class,
            $redisTranslationWriter
        );
    }
}
