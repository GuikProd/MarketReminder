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
use App\UI\Action\Security\Interfaces\AskResetPasswordActionInterface;
use App\UI\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use App\UI\Responder\Security\AskResetPasswordResponder;
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
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var AskResetPasswordTypeHandlerInterface
     */
    private $askResetPasswordTypeHandler;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->askResetPasswordTypeHandler = $this->createMock(AskResetPasswordTypeHandlerInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
    }

    public function testItImplements()
    {
        $formViewMock = $this->createMock(FormView::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $this->formFactory->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn($formViewMock);

        $askResetPasswordAction = new AskResetPasswordAction(
            $this->formFactory,
            $this->askResetPasswordTypeHandler
        );

        static::assertInstanceOf(
            AskResetPasswordActionInterface::class,
            $askResetPasswordAction
        );
    }

    public function testWrongDataProcess()
    {
        $formViewMock = $this->createMock(FormView::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $requestMock = $this->createMock(Request::class);
        $twigMock = $this->createMock(Environment::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $this->formFactory->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn($formViewMock);

        $askResetPasswordAction = new AskResetPasswordAction(
            $this->formFactory,
            $this->askResetPasswordTypeHandler
        );

        $askResetPasswordResponder = new AskResetPasswordResponder($twigMock, $urlGeneratorMock);

        static::assertInstanceOf(
            Response::class,
            $askResetPasswordAction($requestMock, $askResetPasswordResponder)
        );
    }

    public function testRightProcess()
    {
        $formViewMock = $this->createMock(FormView::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $requestMock = $this->createMock(Request::class);
        $twigMock = $this->createMock(Environment::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $this->askResetPasswordTypeHandler->method('handle')->willReturn(true);
        $this->formFactory->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn($formViewMock);
        $urlGeneratorMock->method('generate')->willReturn('/fr/');

        $askResetPasswordAction = new AskResetPasswordAction(
            $this->formFactory,
            $this->askResetPasswordTypeHandler
        );

        $askResetPasswordResponder = new AskResetPasswordResponder($twigMock, $urlGeneratorMock);

        static::assertInstanceOf(
            RedirectResponse::class,
            $askResetPasswordAction($requestMock, $askResetPasswordResponder)
        );
    }
}
