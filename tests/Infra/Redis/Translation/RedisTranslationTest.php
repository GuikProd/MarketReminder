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

namespace App\Tests\Infra\Redis\Translation;

use App\Infra\Redis\Translation\Interfaces\RedisTranslationInterface;
use App\Infra\Redis\Translation\RedisTranslation;
use PHPUnit\Framework\TestCase;

/**
 * Class RedisTranslationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationTest extends TestCase
{
    public function testItImplements()
    {
        $redisTranslation = new RedisTranslation([
            'channel' => 'messages',
            'tag' => 'fr',
            'key' => 'user.creation_success',
            'value' => 'Ce compte a bien été créé.'
        ]);

        static::assertInstanceOf(
            RedisTranslationInterface::class,
            $redisTranslation
        );
    }

    public function testItDefineDefaultAttributes()
    {
        $redisTranslation = new RedisTranslation([
            'channel' => 'messages',
            'tag' => 'fr',
            'key' => 'user.creation_success',
            'value' => 'Ce compte a bien été créé.'
        ]);

        static::assertSame('messages', $redisTranslation->getChannel());
        static::assertSame('fr', $redisTranslation->getTag());
        static::assertSame('user.creation_success', $redisTranslation->getKey());
        static::assertSame('Ce compte a bien été créé.', $redisTranslation->getValue());
    }
}
