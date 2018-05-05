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

namespace App\Infra\Redis;

use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use Predis\Client;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class RedisConnector.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RedisConnector implements RedisConnectorInterface
{
    /**
     * @var TagAwareAdapterInterface
     */
    private $adapter;

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
    public function getAdapter(): TagAwareAdapterInterface
    {
        $connexion = new Client($this->redisDSN);

        $redisAdapter = new RedisAdapter(
            $connexion,
            $this->namespace,
            0
        );

        $redisAdapter::createConnection($this->redisDSN);

        $this->adapter = new TagAwareAdapter(
            $redisAdapter
        );

        return $this->adapter;
    }
}
