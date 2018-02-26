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

namespace tests\Action\Security;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use App\Action\Security\LoginAction;
use App\Responder\Security\LoginResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class LoginActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
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
