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

use App\Application\DependencyInjection\RedisConfiguration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class RedisConfigurationUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class RedisConfigurationUnitTest extends TestCase
{
    public function testItImplements()
    {
        $redisConfiguration = new RedisConfiguration();

        static::assertInstanceOf(
            ConfigurationInterface::class,
            $redisConfiguration
        );
    }
}
