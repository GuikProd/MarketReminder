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

use App\UI\Action\Security\Interfaces\RegisterActionInterface;
use App\UI\Action\Security\RegisterAction;
use App\UI\Form\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Security\RegisterResponder;
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
 * Class RegisterActionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RegisterActionUnitTest extends TestCase
{
    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

    /**
     * @var Environment|null
     */
    private $twig = null;

    /**
     * @var UrlGeneratorInterface|null
     */
    private $urlGenerator = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->presenter = $this->createMock(PresenterInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $registerTypeHandlerMock = $this->createMock(RegisterTypeHandlerInterface::class);

        $formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn(new FormView());

        $registerAction = new RegisterAction(
            $formFactoryMock,
            $registerTypeHandlerMock
        );

        static::assertInstanceOf(RegisterActionInterface::class, $registerAction);
    }

    public function testGoodHandlerProcess()
    {
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $registerTypeHandlerMock = $this->createMock(RegisterTypeHandlerInterface::class);
        $requestMock = $this->createMock(Request::class);

        $formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn(new FormView());

        $registerTypeHandlerMock->method('handle')->willReturn(true);

        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        $registerAction = new RegisterAction(
            $formFactoryMock,
            $registerTypeHandlerMock
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $registerAction(
                $requestMock,
                $registerResponder
            )
        );
    }

    public function testWrongHandlerProcess()
    {
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $registerTypeHandlerMock = $this->createMock(RegisterTypeHandlerInterface::class);
        $requestMock = $this->createMock(Request::class);

        $formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn(new FormView());

        $registerTypeHandlerMock->method('handle')->willReturn(false);

        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        $registerAction = new RegisterAction(
            $formFactoryMock,
            $registerTypeHandlerMock
        );

        static::assertInstanceOf(
            Response::class,
            $registerAction(
                $requestMock,
                $registerResponder
            )
        );
    }
}
