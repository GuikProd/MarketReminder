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
 * Interface RedisTranslationRepositoryInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RedisTranslationRepositoryInterface
{
    /**
     * RedisTranslationRepositoryInterface constructor.
     *
     * @param RedisConnectorInterface $redisConnector
     */
    public function __construct(RedisConnectorInterface $redisConnector);

    /**
     * Allow to retrieve an array of RedisTranslation using the default filename.
     *
     * @param string $filename  The name of the item to retrieve.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return array  The array which contains all the translations stored using the $filename.
     */
    public function getEntries(string $filename): ?array;

    /**
     * Allow to retrieve a single RedisTranslation using the default filename, is key and the locale.
     *
     * @param string $filename The name of the translation file.
     * @param string $locale   The locale used to return the translated content.
     * @param string $key      The key used during the storage process.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return RedisTranslationInterface|null
     */
    public function getSingleEntry(string $filename, string $locale, string $key): ?RedisTranslationInterface;
}
