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

namespace App\Infra\Redis\Translation\Interfaces;

use App\Infra\Redis\Interfaces\RedisConnectorInterface;

/**
 * Interface RedisTranslationReaderInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RedisTranslationReaderInterface
{
    /**
     * RedisTranslationReaderInterface constructor.
     *
     * @param RedisConnectorInterface $redisConnector
     */
    public function __construct(RedisConnectorInterface $redisConnector);
}
