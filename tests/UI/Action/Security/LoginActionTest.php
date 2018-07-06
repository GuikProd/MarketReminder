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

namespace App\Tests\UI\Action\Security;

use App\UI\Action\Security\LoginAction;
use App\UI\Responder\Security\LoginResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

/**
 * Class LoginActionTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class LoginActionTest extends TestCase
{
    public function testInvokeReturn()
    {
        $twigMock = $this->createMock(Environment::class);
        $exceptionMock = $this->createMock(\Exception::class);
        $authenticationUtilsMock = $this->createMock(AuthenticationUtils::class);
        $authenticationUtilsMock->method('getLastUsername')
                                ->willReturn('Toto');
        $authenticationUtilsMock->method('getLastAuthenticationError')
                                ->willReturn($exceptionMock);

        $loginAction = new LoginAction($authenticationUtilsMock);

        $loginResponder = new LoginResponder($twigMock);

        static::assertInstanceOf(
            Response::class,
            $loginAction($loginResponder)
        );
    }
}
