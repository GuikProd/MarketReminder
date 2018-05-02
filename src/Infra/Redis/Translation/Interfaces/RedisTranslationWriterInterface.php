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
     * @param string $tag      The tag used to validate the cache entry.
     * @param string $fileName The name of the file to cache (used as a key inside the cache).
     * @param array  $values   The array of values to cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException @see RedisAdapter::getItem()
     *
     * @return bool  If the write process has succeed.
     */
    public function write(string $tag, string $fileName, array $values): bool;

    /**
     * @param string $tag      The tag to store.
     * @param string $fileName The filename used as a value (as a FK for relation).
     *
     * @throws \Psr\Cache\InvalidArgumentException @see RedisAdapter::getItem()
     *
     * @return bool  If the tag has been stored.
     */
    public function storeTag(string $tag, string $fileName): bool;

    /**
     * Return all the tags saved in the cache (by default, each tag is saved in the cache).
     *
     * @return array
     */
    public function getTags(): array;
}
