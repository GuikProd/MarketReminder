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

namespace App\Tests\Infra\Redis;

use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\RedisConnector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class RedisConnectorUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisConnectorUnitTest extends TestCase
{
    /**
     * @var string
     */
    private $redisDSN;

    /**
     * @var string
     */
    private $redisNamespace;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->redisDSN = 'redis://localhost:6973';
        $this->redisNamespace = "test";
    }

    public function testItImplements()
    {
        $redisConnector = new RedisConnector(
            $this->redisDSN,
            $this->redisNamespace
        );

        static::assertInstanceOf(
            RedisConnectorInterface::class,
            $redisConnector
        );
    }

    public function testItConfigureConnectionAndReturnAdapter()
    {
        $redisConnector = new RedisConnector(
            $this->redisDSN,
            $this->redisNamespace
        );

        static::assertInstanceOf(
            TagAwareAdapterInterface::class,
            $redisConnector->getAdapter()
        );
    }
}
