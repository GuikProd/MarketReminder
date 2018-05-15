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
use Psr\Cache\CacheItemInterface;

/**
 * Interface RedisTranslationWriterInterface.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RedisTranslationWriterInterface
{
    /**
     * RedisTranslationWriterInterface constructor.
     *
     * @param RedisConnectorInterface $redisConnector
     */
    public function __construct(RedisConnectorInterface $redisConnector);

    /**
     * Allow to store a new item in the Cache, a RedisTranslation is created and stored.
     *
     * For security purpose, the tag is generated using a @see Uuid::uuid4(), a check is done for
     * coherence purpose inside the "tag table".
     *
     * For reading purpose, the item is tagged with the Uuid tag.
     *
     * @param string $locale   The locale used by the translation.
     * @param string $channel  The channel used by the item (used for retrieving process).
     * @param string $fileName The name of the file to cache (used as a key inside the cache along with the tag).
     * @param array  $values   The array of values to cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException @see RedisAdapter::getItem()
     *
     * @return bool If the write process has succeed or if the cache item is fresh.
     */
    public function write(string $locale, string $channel, string $fileName, array $values): bool;

    /**
     * Allow to decide if the new values should be stored in the cache, the check is done
     * via the CacheItem keys along with the new values keys.
     *
     * @param CacheItemInterface $cacheValues  The cache item already stored.
     * @param array              $values       The values to check before caching it.
     *
     * @return bool If the cache content is fresh or stale.
     */
    public function isCacheContentValid(CacheItemInterface $cacheValues, array $values): bool;
}
