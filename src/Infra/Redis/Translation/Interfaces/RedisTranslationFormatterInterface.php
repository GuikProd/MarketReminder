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
 * Interface RedisTranslationFormatterInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RedisTranslationFormatterInterface
{
    /**
     * RedisTranslationFormatterInterface constructor.
     *
     * @param RedisConnectorInterface $connector
     */
    public function __construct(RedisConnectorInterface $connector);

    /**
     * @param string $channel
     * @param string $key
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException @see RedisCache::get
     * @throws \InvalidArgumentException                 Both if the $channel isn't an array or if the $key does not exist.
     *
     * @return RedisTranslationInterface
     */
    public function formatEntry(string $channel, string $key): RedisTranslationInterface;
}
