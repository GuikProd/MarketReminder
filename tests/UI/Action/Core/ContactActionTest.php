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

namespace App\Tests\UI\Action\Core;

use App\UI\Action\Core\ContactAction;
use App\UI\Responder\Core\ContactResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class ContactActionTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ContactActionTest extends TestCase
{
    public function testInvokeReturn()
    {
        $twigMock = $this->createMock(Environment::class);
        $requestMock = $this->createMock(Request::class);

        $contactAction = new ContactAction();

        $contactResponder = new ContactResponder($twigMock);

        static::assertInstanceOf(
            Response::class,
            $contactAction($requestMock, $contactResponder)
        );
    }
}
