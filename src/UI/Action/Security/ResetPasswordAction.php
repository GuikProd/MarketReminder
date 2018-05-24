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

namespace App\UI\Action\Security;

use App\Application\Event\SessionMessageEvent;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Security\Interfaces\ResetPasswordActionInterface;
use App\UI\Form\FormHandler\Interfaces\ResetPasswordTypeHandlerInterface;
use App\UI\Form\Type\ResetPasswordType;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResetPasswordAction.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * @Route(
 *     name="web_reset_password",
 *     path="/reset-password/{token}",
 *     requirements={
 *         "token": "\S+"
 *     }
 * )
 */
final class ResetPasswordAction implements ResetPasswordActionInterface
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
     * @var ResetPasswordTypeHandlerInterface
     */
    private $resetPasswordTypeHandler;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FormFactoryInterface $formFactory,
        ResetPasswordTypeHandlerInterface $resetPasswordTypeHandler,
        UserRepositoryInterface $userRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->resetPasswordTypeHandler = $resetPasswordTypeHandler;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        ResetPasswordResponderInterface $responder
    ): Response {

        if (!$user = $this->userRepository->getUserByResetPasswordToken($request->attributes->get('token'))) {

            $this->eventDispatcher->dispatch(
                SessionMessageEvent::NAME,
                new SessionMessageEvent(
                    'failure',
                    'user.notification.wrong_reset_password_token'
                )
            );

            return $responder($request, true);
        }

        $form = $this->formFactory->create(ResetPasswordType::class)
                                  ->handleRequest($request);

        if ($this->resetPasswordTypeHandler->handle($form, $user)) {
            return $responder($request, true);
        }

        return $responder($request, false, $form);
    }
}
