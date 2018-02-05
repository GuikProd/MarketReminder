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

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormView;
use App\Action\Security\RegisterAction;
use Symfony\Component\Form\FormInterface;
use App\Responder\Security\RegisterResponder;
use Symfony\Component\HttpFoundation\Request;
use App\Builder\Interfaces\UserBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class RegisterActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterActionTest extends TestCase
{
    public function testReturn()
    {
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);

        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $registerTypeHandlerMock = $this->createMock(RegisterTypeHandlerInterface::class);

        $requestMock = $this->createMock(Request::class);

        $userBuilderMock = $this->createMock(UserBuilderInterface::class);

        $sessionMock = $this->createMock(SessionInterface::class);

        $registerResponderMock = $this->createMock(RegisterResponder::class);

        $formInterfaceMock = $this->createMock(FormInterface::class);

        $formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn(new FormView());

        $registerAction = new RegisterAction(
            $formFactoryMock,
            $urlGeneratorMock,
            $eventDispatcherMock,
            $registerTypeHandlerMock
        );

        static::assertNull(
            $registerAction(
                $requestMock,
                $userBuilderMock,
                $sessionMock,
                $registerResponderMock
            )
        );
    }
}
