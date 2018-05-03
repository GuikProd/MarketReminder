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
     * Allow to store a new item in the Cache, for security purpose,
     * the tag should not be already used by an item, if yes, the item isn't stored.
     *
     * For reading purpose, the item is tagged with the original tag and a timestamp.
     *
     * @param string $tag      The tag used to validate the cache entry.
     * @param string $channel  The channel used by the item (used for retrieving process).
     * @param string $fileName The name of the file to cache (used as a key inside the cache along with the tag).
     * @param array  $values   The array of values to cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException @see RedisAdapter::getItem()
     *
     * @return bool  If the write process has succeed.
     */
    public function write(string $tag, string $channel, string $fileName, array $values): bool;

    /**
     * Allow to store every tag used by the items already persisted.
     *
     * For simplicity purpose, the timestamp tag is persisted.
     *
     * @param string $tag      The tag to store.
     * @param string $fileName The filename used as a value (as a FK for relation).
     *
     * @throws \Psr\Cache\InvalidArgumentException @see RedisAdapter::getItem()
     *
     * @return bool  If the tag has been stored.
     */
    public function storeTag(string $tag, string $fileName): bool;
}
