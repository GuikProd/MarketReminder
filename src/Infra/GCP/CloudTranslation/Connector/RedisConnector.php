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

namespace App\Infra\GCP\CloudTranslation\Connector;

use Redis;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class RedisConnector.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RedisConnector implements RedisConnectorInterface, ConnectorInterface
{
    /**
     * @var TagAwareAdapterInterface
     */
    private $adapter;

    /**
     * @var Redis
     */
    private $connection;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $redisDSN;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $redisDSN,
        string $namespace
    ) {
        $this->redisDSN = $redisDSN;
        $this->namespace = $namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter(): CacheItemPoolInterface
    {
        $this->connection = RedisAdapter::createConnection($this->redisDSN, [
            'class' => Redis::class,
            'persistent_connection' => 1,
            'persistent_id' => md5(str_rot13($this->namespace))
        ]);

        $redisAdapter = new RedisAdapter(
            $this->connection,
            $this->namespace,
            0
        );

        $this->adapter = new TagAwareAdapter($redisAdapter);

        return $this->adapter;
    }
}
