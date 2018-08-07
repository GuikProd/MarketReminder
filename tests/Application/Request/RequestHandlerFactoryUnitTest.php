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

namespace App\Tests\Application\Request;

use App\Application\Request\Interfaces\RequestHandlerFactoryInterface;
use App\Application\Request\RequestHandlerFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestHandlerFactoryUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RequestHandlerFactoryUnitTest extends TestCase
{
    public function testItImplements()
    {
        $factory = new RequestHandlerFactory();

        static::assertInstanceOf(RequestHandlerFactoryInterface::class, $factory);
    }


}
