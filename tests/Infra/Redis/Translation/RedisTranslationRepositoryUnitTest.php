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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class RedisTranslationRepositoryUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationRepositoryUnitTest extends TestCase
{
    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->redisConnector = $this->getMockBuilder(RedisConnectorInterface::class)
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->redisConnector->method('getAdapter')->willReturn(TagAwareAdapterInterface::class);
    }

    public function testItImplements()
    {
        $redisTranslationRepository = new RedisTranslationRepository($this->redisConnector);

        static::assertInstanceOf(
            RedisTranslationRepositoryInterface::class,
            $redisTranslationRepository
        );
    }
    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('messages.fr.yaml', 'fr', 'home.text');
    }
}
