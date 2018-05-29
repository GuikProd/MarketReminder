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

namespace App\Tests\UI\Responder\Core;

use App\UI\Responder\Core\ContactResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class ContactResponderTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ContactResponderTest extends TestCase
{
    public function testResponseIsReturned()
    {
        $twigMock = $this->createMock(Environment::class);

        $contactResponder = new ContactResponder($twigMock);

        static::assertInstanceOf(
            Response::class,
            $contactResponder()
        );
    }
}
