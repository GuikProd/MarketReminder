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

namespace App\Infra\Redis\Translation;

use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;

/**
 * Class RedisTranslationRepository.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RedisTranslationRepository implements RedisTranslationRepositoryInterface
{
    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * {@inheritdoc}
     */
    public function __construct(RedisConnectorInterface $redisConnector)
    {
        $this->redisConnector = $redisConnector;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntries(string $filename): ?array
    {
        $cacheItem = $this->redisConnector->getAdapter()->getItem($filename);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSingleEntry(string $filename, string $locale, string $key): ?RedisTranslationInterface
    {
        $cacheItem = $this->redisConnector->getAdapter()->getItem($filename);

        if ($cacheItem->isHit()) {
            foreach ($cacheItem->get() as $item => $value) {
                return $value->getKey() === $key ? $value : null;
            }
        }

        return null;
    }
}
