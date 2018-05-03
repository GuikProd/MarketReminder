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

    /**
     * Allow to retrieve a single cache item and transform it into a RedisTranslation.
     *
     * @param string $filename  The name of the item to retrieve.
     * @param array  $values    The values used to compare the item content.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return RedisTranslationInterface  The cache item transformed if the cache is valid.
     * @return null                       If the cache item isn't valid, a new entry must be generated.
     */
    public function getEntry(string $filename, array $values): ?RedisTranslationInterface;
}
