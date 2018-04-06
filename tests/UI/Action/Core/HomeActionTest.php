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

namespace App\Tests\UI\Action\Core;

use App\UI\Action\Core\HomeAction;
use App\Responder\Core\HomeResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class HomeActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeActionTest extends TestCase
{
    public function testReturn()
    {
        $requestMock = $this->createMock(Request::class);
        $twigMock = $this->createMock(Environment::class);

        $twigMock->method('getCharset')
                 ->willReturn('UTF-8');

        $homeResponder = new HomeResponder($twigMock);

        $homeAction = new HomeAction();

        static::assertInstanceOf(
            Response::class,
            $homeAction($requestMock, $homeResponder)
        );
    }
}
