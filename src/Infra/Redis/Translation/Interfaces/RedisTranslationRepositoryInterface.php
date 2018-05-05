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
use Symfony\Component\Serializer\SerializerInterface;

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
     * @param SerializerInterface     $serializer
     * @param RedisConnectorInterface $redisConnector
     */
    public function __construct(SerializerInterface $serializer, RedisConnectorInterface $redisConnector);

    /**
     * Allow to retrieve a single cache item and transform it into a RedisTranslation.
     *
     * @param string $filename  The name of the item to retrieve.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return array  The array which contains all the translations stored using the $filename.
     */
    public function getEntry(string $filename): ?array;
}
