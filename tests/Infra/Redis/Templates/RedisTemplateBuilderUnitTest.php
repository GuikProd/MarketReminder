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

namespace App\Tests\Infra\Redis\Templates;

use App\Infra\Redis\Templates\Interfaces\RedisTemplateBuilderInterface;
use App\Infra\Redis\Templates\RedisTemplateBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class RedisTemplateBuilderUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTemplateBuilderUnitTest extends TestCase
{
    public function testItImplements()
    {
        $redisTemplateBuilder = new RedisTemplateBuilder(__DIR__.'/../../../_assets/');

        static::assertInstanceOf(
            RedisTemplateBuilderInterface::class,
            $redisTemplateBuilder
        );
    }
}
