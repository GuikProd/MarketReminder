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

namespace App\Tests\Application\DependencyInjection;

use App\Application\DependencyInjection\RedisExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Class RedisExtensionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class RedisExtensionUnitTest extends TestCase
{
    public function testItExtends()
    {
        $redisExtension = new RedisExtension();

        static::assertInstanceOf(Extension::class, $redisExtension);
    }
}
