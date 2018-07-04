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

namespace App\UI\Form\FormHandler;

use App\Application\Event\SessionMessageEvent;
use App\Application\Event\UserEvent;
use App\Application\Helper\Security\TokenGeneratorHelper;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use App\UI\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class AskResetPasswordTypeHandler.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class AskResetPasswordTypeHandler implements AskResetPasswordTypeHandlerInterface
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
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $session,
        UserRepositoryInterface $userRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
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

            $this->eventDispatcher->dispatch(
                UserEvent::USER_ASK_RESET_PASSWORD,
                new UserEvent($user)
            );

            $this->eventDispatcher->dispatch(
                SessionMessageEvent::NAME,
                new SessionMessageEvent(
                    'success',
                    'user.reset_password.success'
                )
            );

            return true;
        }

        return false;
    }
}
