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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationReaderInterface;

/**
 * Class RedisTranslationReader.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationReader implements RedisTranslationReaderInterface
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
    public function getEntry(string $filename, array $values): ?RedisTranslationInterface
    {
        $cacheItems = $this->redisConnector->getAdapter()->getItem($filename);

        foreach ($cacheItems->get()['value'] as $item => $cachedValue) {
            foreach ($values as $entry) {
                if ($entry === $cachedValue) {
                    return new RedisTranslation([
                        'channel' => $cacheItems->get()['channel'],
                        'tag' => $cacheItems->get()['tag']['tag'],
                        'key' => $item,
                        'value' => $cachedValue
                    ]);
                }

                return null;
            }
        }
    }
}
