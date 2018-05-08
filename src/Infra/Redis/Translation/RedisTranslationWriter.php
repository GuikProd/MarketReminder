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
use Psr\Cache\CacheItemInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class RedisTranslationWriter.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RedisTranslationWriter implements RedisTranslationWriterInterface
{
    /**
     * @var array
     */
    private $entries;

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
    public function write(string $channel, string $fileName, array $values): bool
    {
        $cacheItem = $this->redisConnector->getAdapter()->getItem($fileName);

        if ($cacheItem->isHit()) {
            $toStore = $this->checkContent($cacheItem, $values);

            if (!$toStore) {
                return false;
            }

            $this->redisConnector->getAdapter()->invalidateTags($cacheItem->getPreviousTags());

            $this->redisConnector->getAdapter()->deleteItem($cacheItem->getKey());
        }

        $tag = Uuid::uuid4()->toString();

        foreach ($values as $item => $value) {
            $this->entries[] = new RedisTranslation([
                'channel' => $channel,
                'tag' => $tag,
                'key' => $item,
                'value' => $value
            ]);
        }

        $cacheItem->set($this->entries);
        $cacheItem->tag($tag);

        $this->redisConnector->getAdapter()->save($cacheItem);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function checkContent(CacheItemInterface $cacheValues, array $values): bool
    {
        static $translationKey = [];
        static $translationContent = [];
        static $toCheckKey = [];
        static $toCheckContent = [];

        foreach ($cacheValues->get() as $item => $value) {
            $translationKey[] = $value->getKey();
            $translationContent[] = $value->getValue();
        }

        foreach ($values as $item => $value) {
            $toCheckKey[] = $item;
            $toCheckContent[] = $value;
        }

        $finalArray = array_combine($translationKey, $translationContent);
        $finalCheckArray = array_combine($toCheckKey, $toCheckContent);

        if (count(array_diff($finalArray, $finalCheckArray)) > 0) {
            return true;
        }

        return false;
    }
}
