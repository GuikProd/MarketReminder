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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;

/**
 * Class RedisTranslationWriter.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWriter implements RedisTranslationWriterInterface
{
    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var array
     */
    private $tags = [];

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
    public function write(string $tag, string $fileName, array $values): bool
    {
        $this->tags[] = $tag;

        if (!$this->storeTag($tag, $fileName)) {
            return false;
        }

        $cacheItem = $this->redisConnector->getAdapter()->getItem($fileName);

        if ($cacheItem->isHit()) {
            return false;
        }

        $cacheItem->set($values);
        $cacheItem->tag($tag);

        $this->redisConnector->getAdapter()->save($cacheItem);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function storeTag(string $tag, string $fileName): bool
    {
        $cacheItem = $this->redisConnector->getAdapter()->getItem($tag);

        if ($cacheItem->isHit()) {
            return false;
        }

        $cacheItem->set($fileName);

        $this->redisConnector->getAdapter()->save($cacheItem);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
