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
use Symfony\Component\Form\FormView;
use App\Action\Security\RegisterAction;
use Symfony\Component\Form\FormInterface;
use App\Responder\Security\RegisterResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Builder\Interfaces\UserBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class RegisterActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterActionTest extends TestCase
{
    public function testReturn()
    {
        $requestMock = $this->createMock(Request::class);
        $twigMock = $this->createMock(Environment::class);
        $sessionMock = $this->createMock(SessionInterface::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $userBuilderMock = $this->createMock(UserBuilderInterface::class);
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $registerTypeHandlerMock = $this->createMock(RegisterTypeHandlerInterface::class);

        $formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn(new FormView());

        $registerResponder = new RegisterResponder($twigMock);

        $registerAction = new RegisterAction(
            $formFactoryMock,
            $urlGeneratorMock,
            $eventDispatcherMock,
            $registerTypeHandlerMock
        );

        static::assertInstanceOf(
            Response::class,
            $registerAction(
                $requestMock,
                $userBuilderMock,
                $sessionMock,
                $registerResponder
            )
        );
    }

    public function testHandlerProcess()
    {
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);

        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $registerTypeHandlerMock = $this->createMock(RegisterTypeHandlerInterface::class);

        $requestMock = $this->createMock(Request::class);

        $userBuilderMock = $this->createMock(UserBuilderInterface::class);

        $sessionMock = new Session(new MockArraySessionStorage());

        $registerResponderMock = $this->createMock(RegisterResponder::class);

        $formInterfaceMock = $this->createMock(FormInterface::class);

        $formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn(new FormView());

        $registerTypeHandlerMock->method('handle')
                                ->willReturn(true);

        $urlGeneratorMock->method('generate')->willReturn('/fr/');

        $registerAction = new RegisterAction(
            $formFactoryMock,
            $urlGeneratorMock,
            $eventDispatcherMock,
            $registerTypeHandlerMock
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $registerAction(
                $requestMock,
                $userBuilderMock,
                $sessionMock,
                $registerResponderMock
            )
        );
    }
}
