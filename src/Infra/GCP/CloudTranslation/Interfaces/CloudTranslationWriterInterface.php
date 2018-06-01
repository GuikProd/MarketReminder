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

namespace App\Infra\GCP\CloudTranslation\Interfaces;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use Psr\Cache\CacheItemInterface;

/**
 * Interface CloudTranslationWriterInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationWriterInterface
{
    /**
     * CloudTranslationWriterInterface constructor.
     *
     * @param CloudTranslationBackupWriterInterface $backupWriter
     * @param ConnectorInterface $redisConnector
     */
    public function __construct(
        CloudTranslationBackupWriterInterface $backupWriter,
        ConnectorInterface $redisConnector
    );

    /**
     * Allow to store a new item in the Cache, a CloudTranslationItem is created and stored.
     *
     * For security purpose, the tag is generated using a @see Uuid::uuid4().
     *
     * For reading purpose, the item is tagged with the Uuid tag.
     *
     * @internal A recursive call is done in order to invalidate the item if is
     *           content is out of date, this way, the cache is cleaned.
     *
     * @param string $locale   The locale used by the translation.
     * @param string $channel  The channel used by the item (used for retrieving process).
     * @param string $fileName The name of the file to cache (used as a key inside the cache along with the tag).
     * @param array  $values   The array of values to cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException @see TagAwareAdapter::getItem()
     *
     * @return bool If the write process has succeed or if the cache item is fresh.
     */
    public function write(string $locale, string $channel, string $fileName, array $values): bool;

    /**
     * Allow to decide if the new values should be stored in the cache, the check is done
     * via the CacheItem keys along with the new values keys.
     *
     * @param CacheItemInterface $cacheValues The cache item already stored.
     * @param array              $values      The values to check before caching it.
     *
     * @return bool If the cache content is fresh or stale.
     */
    public function isCacheContentValid(CacheItemInterface $cacheValues, array $values): bool;
}
