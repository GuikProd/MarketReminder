<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Infra\GCP\CloudTranslation\Connector;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

/**
 * Class RedisConnectorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RedisConnectorUnitTest extends TestCase
{
    /**
     * @var string|null
     */
    private $redisDSN = null;

    /**
     * @var string|null
     */
    private $redisNamespace = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->redisDSN = 'localhost:6973';
        $this->redisNamespace = "test";
    }

    public function testItImplements()
    {
        $redisConnector = new RedisConnector(
            $this->redisDSN,
            $this->redisNamespace
        );

        static::assertInstanceOf(
            ConnectorInterface::class,
            $redisConnector
        );
        static::assertInstanceOf(
            RedisConnectorInterface::class,
            $redisConnector
        );
    }

    /**
     * This test can throw an InvalidArgumentException due to the fact
     * that the connection is creating using a @see RedisAdapter::createConnection
     *
     * @throws InvalidArgumentException
     */
    public function testItConfigureConnectionAndReturnAdapter()
    {
        static::expectException(InvalidArgumentException::class);

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
