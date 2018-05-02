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

namespace App\Infra\Redis\Interfaces;

use Symfony\Component\Cache\Simple\RedisCache;

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
     * @param string $redisDSN  The DSN of the Redis server to use.
     */
    public function __construct(string $redisDSN);

    /**
     * Instantiate the RedisCache along with the @see Predis class.
     *
     * @return RedisCache
     */
    public function getAdapter(): RedisCache;
}
