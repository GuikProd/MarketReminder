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
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisConnectorTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisConnectorTest extends KernelTestCase
{
    /**
     * @var string
     */
    private $redisDSN;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->redisDSN = static::$kernel->getContainer()->getParameter('redis.dsn');
    }

    public function testItImplements()
    {
        $redisConnector = new RedisConnector($this->redisDSN);

        static::assertInstanceOf(RedisConnectorInterface::class, $redisConnector);
    }

    public function testItConfigureConnection()
    {
        $redisConnector = new RedisConnector($this->redisDSN);

        static::assertInstanceOf(
            Client::class,
            $redisConnector->getAdapter()->getConnection()
        );
    }
}
