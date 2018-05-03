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

/**
 * Class RedisConnector.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisConnector implements RedisConnectorInterface
{
    /**
     * @var RedisAdapter
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
    public function getAdapter(): RedisAdapter
    {
        $connexion = new Client($this->redisDSN);

        $this->adapter = new RedisAdapter(
            $connexion,
            $this->namespace,
            0
        );

        $this->adapter::createConnection($this->redisDSN);

        return $this->adapter;
    }
}
