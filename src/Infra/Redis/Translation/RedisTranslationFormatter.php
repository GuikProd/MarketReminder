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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationFormatterInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationInterface;

/**
 * Class RedisTranslationFormatter.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationFormatter implements RedisTranslationFormatterInterface
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
    public function formatEntry(string $channel, string $key = ''): RedisTranslationInterface
    {
        $entry = $this->redisConnector->getAdapter()->get($channel);

        if (!is_array($entry)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'The entry must be stored as an array !'
                )
            );
        }

        foreach ($entry as $item => $value) {
            foreach ($value as $entry => $translation) {
                foreach ($translation as $toStoreText => $toStoreValue) {
                    if ($key === $toStoreText) {
                        return new RedisTranslation([
                            'channel' => $item,
                            'tag' => $entry,
                            'key' => $toStoreText,
                            'value' => $toStoreValue
                        ]);
                    }

                    throw new \InvalidArgumentException(
                        \sprintf(
                            'The key must be stored in the cache to be returned ! Given %s', $key
                        )
                    );
                }
            }
        }
    }
}
