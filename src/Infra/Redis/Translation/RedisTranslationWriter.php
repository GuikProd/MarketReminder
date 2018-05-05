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
use Symfony\Component\Serializer\SerializerInterface;

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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        SerializerInterface $serializer,
        RedisConnectorInterface $redisConnector
    ) {
        $this->serializer = $serializer;
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
        }

        $tag = Uuid::uuid4()->toString();

        foreach ($values as $item => $value) {
            $translation = new RedisTranslation([
                'channel' => $channel,
                'tag' => $tag,
                'key' => $item,
                'value' => $value
            ]);

            $this->entries[] = $this->serializer->serialize($translation, 'json');
        }

        $cacheItem->set($this->entries);
        $cacheItem->tag([$tag, (string) time()]);

        $this->redisConnector->getAdapter()->save($cacheItem);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function checkContent(CacheItemInterface $cacheValues, array $values): bool
    {
        static $translationContent = [];
        static $toCheckContent = [];

        foreach ($cacheValues->get() as $item => $value) {
            $redisTranslation = $this->serializer->deserialize($value, RedisTranslation::class, 'json');

            $translationContent[] = $redisTranslation->getKey();
        }

        foreach ($values as $item => $value) {
            $toCheckContent[] = $item;
        }

        $finalTranslatedArray = array_unique($translationContent);
        $finalToCheckArray = array_unique($toCheckContent);

        if (count(array_diff($finalTranslatedArray, $finalToCheckArray)) > 0) {
            return true;
        }

        return false;
    }
}
