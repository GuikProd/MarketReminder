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

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Security\Interfaces\ResetPasswordActionInterface;
use App\UI\Action\Security\ResetPasswordAction;
use App\UI\Presenter\Security\Interfaces\ResetPasswordPresenterInterface;
use App\UI\Responder\Security\ResetPasswordResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ResetPasswordActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordActionTest extends TestCase
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ResetPasswordPresenterInterface
     */
    private $resetPasswordPresenter;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->resetPasswordPresenter = $this->createMock(ResetPasswordPresenterInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $resetPasswordAction = new ResetPasswordAction(
            $this->eventDispatcher,
            $this->formFactory,
            $this->resetPasswordPresenter,
            $this->userRepository
        );

        static::assertInstanceOf(
            ResetPasswordActionInterface::class,
            $resetPasswordAction
        );
    }

    public function testItReturnDuringWrongProcess()
    {
        $resetPasswordResponder = new ResetPasswordResponder($this->urlGenerator);

        $resetPasswordAction = new ResetPasswordAction(
            $this->eventDispatcher,
            $this->formFactory,
            $this->resetPasswordPresenter,
            $this->userRepository
        );

        $this->userRepository->method('getUserByResetPasswordToken')->willReturn(null);

        static::assertInstanceOf(
            RedirectResponse::class,
            $resetPasswordAction($this->request, $resetPasswordResponder)
        );
    }

    public function testItReturnDuringRightProcess()
    {
        $resetPasswordResponder = new ResetPasswordResponder($this->urlGenerator);

        $resetPasswordAction = new ResetPasswordAction(
            $this->eventDispatcher,
            $this->formFactory,
            $this->resetPasswordPresenter,
            $this->userRepository
        );

        $this->userRepository->method('getUserByResetPasswordToken')->willReturn(
            $this->createMock(UserInterface::class)
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $resetPasswordAction($this->request, $resetPasswordResponder)
        );
    }
}
