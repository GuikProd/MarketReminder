<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Infra\GCP\CloudTranslation\Domain\Models;

use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationItemInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationItemUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationItemUnitTest extends TestCase
{
    public function testItImplements()
    {
        $redisTranslation = new CloudTranslationItem([
            '_locale' => 'fr',
            'channel' => 'messages',
            'tag' => 'fr',
            'key' => 'user.creation_success',
            'value' => 'Ce compte a bien été créé.'
        ]);

        static::assertInstanceOf(
            CloudTranslationItemInterface::class,
            $redisTranslation
        );
    }

    public function testItDefineDefaultAttributes()
    {
        $redisTranslation = new CloudTranslationItem([
            '_locale' => 'fr',
            'channel' => 'messages',
            'tag' => 'fr',
            'key' => 'user.creation_success',
            'value' => 'Ce compte a bien été créé.'
        ]);

        static::assertSame('messages', $redisTranslation->getChannel());
        static::assertSame('fr', $redisTranslation->getTag());
        static::assertSame('user.creation_success', $redisTranslation->getKey());
        static::assertSame('Ce compte a bien été créé.', $redisTranslation->getValue());
        static::assertArrayHasKey('_locale', $redisTranslation->getOptions());
    }
}
