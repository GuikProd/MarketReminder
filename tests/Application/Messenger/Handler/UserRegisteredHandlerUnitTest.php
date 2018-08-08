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

namespace App\Tests\Application\Messenger\Handler;

use App\Application\Messenger\Handler\Interfaces\UserRegisteredHandlerInterface;
use App\Application\Messenger\Handler\UserRegisteredHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class UserRegisteredHandlerUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserRegisteredHandlerUnitTest extends TestCase
{
    public function testItImplements()
    {
        $handler = new UserRegisteredHandler();

        static::assertInstanceOf(UserRegisteredHandlerInterface::class, $handler);
    }
}
