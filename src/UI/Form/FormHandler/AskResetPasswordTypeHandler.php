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

namespace App\UI\Form\FormHandler;

use App\Application\Event\SessionMessageEvent;
use App\Application\Event\User\Interfaces\UserAskResetPasswordEventInterface;
use App\Application\Event\User\UserAskResetPasswordEvent;
use App\Application\Helper\Security\TokenGeneratorHelper;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use App\UI\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use App\UI\Presenter\User\Interfaces\UserEmailPresenterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class AskResetPasswordTypeHandler.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeHandler implements AskResetPasswordTypeHandlerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserEmailPresenterInterface
     */
    private $userEmailPresenter;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $session,
        UserEmailPresenterInterface $presenter,
        UserRepositoryInterface $userRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
        $this->userEmailPresenter = $presenter;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $askResetPasswordType): bool
    {
        if ($askResetPasswordType->isSubmitted() && $askResetPasswordType->isValid()) {

            $user = $this->userRepository->getUserByUsernameAndEmail(
                $askResetPasswordType->getData()->username,
                $askResetPasswordType->getData()->email
            );

            $userResetPasswordToken = new UserResetPasswordToken(
                TokenGeneratorHelper::generateResetPasswordToken(
                    $askResetPasswordType->getData()->username,
                    $askResetPasswordType->getData()->email
                )
            );

            $user->askForPasswordReset($userResetPasswordToken);

            $this->userRepository->flush();

            $this->userEmailPresenter->prepareOptions([
                'email' => [
                    'content' => 'user.ask_reset_password.content',
                    'link' => [
                        'text' => 'user.ask_reset_password.link.text'
                    ],
                    'header' => 'user.ask_reset_password.header',
                    'subject' => 'user.reset_password.header',
                    'title' => 'user.ask_reset_password.header',
                    'to' => $user->getEmail()
                ],
            ]);

            $this->eventDispatcher->dispatch(
                UserAskResetPasswordEventInterface::NAME,
                new UserAskResetPasswordEvent(
                    $user,
                    $this->userEmailPresenter
                )
            );

            $this->eventDispatcher->dispatch(
                SessionMessageEvent::NAME,
                new SessionMessageEvent('success', 'user.reset_password.success')
            );

            return true;
        }

        return false;
    }
}
