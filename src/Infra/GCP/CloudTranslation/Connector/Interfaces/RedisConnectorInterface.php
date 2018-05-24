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

namespace App\Infra\GCP\CloudTranslation\Connector\Interfaces;

/**
 * Interface RedisConnectorInterface.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RedisConnectorInterface
{
    /**
     * RedisConnectorInterface constructor.
     *
     * @param string $redisDSN   The DSN of the Redis server to use.
     * @param string $namespace  The namespace used by the Redis server.
     */
    public function __construct(string $redisDSN, string $namespace);
}
