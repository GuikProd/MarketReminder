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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RedisTranslationRepository.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RedisTranslationRepository implements RedisTranslationRepositoryInterface
{
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
    public function getEntry(string $filename): ?array
    {
        static $translations = [];

        $cacheItem = $this->redisConnector->getAdapter()->getItem($filename);

        if ($cacheItem->isHit()) {
            foreach ($cacheItem->get() as $item => $value) {
                $translations[] = $this->serializer->deserialize(
                    $value,
                    RedisTranslation::class,
                    'json'
                );
            }

            return $translations;
        }

        return null;
    }
}
