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

namespace App\Tests\UI\Action\Security;

use App\UI\Action\Security\AskResetPasswordAction;
use App\UI\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use App\UI\Responder\Security\AskResetPasswordResponder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class AskResetPasswordActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordActionTest extends TestCase
{
    public function testWrongDataProcess()
    {
        $askResetPasswordTypeHandlerMock = $this->createMock(AskResetPasswordTypeHandlerInterface::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $formViewMock = $this->createMock(FormView::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $requestMock = $this->createMock(Request::class);
        $twigMock = $this->createMock(Environment::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $formFactoryMock->method('create')
                        ->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')
                          ->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')
                          ->willReturn($formViewMock);

        $askResetPasswordAction = new AskResetPasswordAction(
            $formFactoryMock,
            $entityManagerMock,
            $askResetPasswordTypeHandlerMock
        );

        $askResetPasswordResponder = new AskResetPasswordResponder($twigMock, $urlGeneratorMock);

        static::assertInstanceOf(
            Response::class,
            $askResetPasswordAction($requestMock, $askResetPasswordResponder)
        );
    }

    public function testRightProcess()
    {
        $askResetPasswordTypeHandlerMock = $this->createMock(AskResetPasswordTypeHandlerInterface::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $formViewMock = $this->createMock(FormView::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $requestMock = $this->createMock(Request::class);
        $twigMock = $this->createMock(Environment::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $askResetPasswordTypeHandlerMock->method('handle')->willReturn(true);
        $formFactoryMock->method('create')
                        ->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')
                          ->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')
                          ->willReturn($formViewMock);
        $urlGeneratorMock->method('generate')->willReturn('/fr/');

        $askResetPasswordAction = new AskResetPasswordAction(
            $formFactoryMock,
            $entityManagerMock,
            $askResetPasswordTypeHandlerMock
        );

        $askResetPasswordResponder = new AskResetPasswordResponder($twigMock, $urlGeneratorMock);

        static::assertInstanceOf(
            RedirectResponse::class,
            $askResetPasswordAction($requestMock, $askResetPasswordResponder)
        );
    }
}
