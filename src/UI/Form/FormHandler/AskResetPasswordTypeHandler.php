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
use App\Application\Event\User\UserResetPasswordEvent;
use App\Application\Helper\Security\TokenGeneratorHelper;
use App\Domain\Models\User;
use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use App\UI\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class AskResetPasswordTypeHandler
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeHandler implements AskResetPasswordTypeHandlerInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $askResetPasswordType): bool
    {
        if ($askResetPasswordType->isSubmitted() && $askResetPasswordType->isValid()) {

            $user = $this->entityManager->getRepository(User::class)
                                        ->getUserByUsernameAndEmail(
                                            $askResetPasswordType->getData()->username,
                                            $askResetPasswordType->getData()->email
                                        );

            if (!$user) {
                $this->eventDispatcher->dispatch(
                    SessionMessageEvent::NAME,
                    new SessionMessageEvent('failure', 'user.not_found')
                );

                return false;
            }

            $userResetPasswordToken = new UserResetPasswordToken(
                TokenGeneratorHelper::generateResetPasswordToken(
                    $askResetPasswordType->getData()->username,
                    $askResetPasswordType->getData()->email
                )
            );

            $user->askForPasswordReset($userResetPasswordToken);

            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(
                UserResetPasswordEvent::NAME,
                new UserResetPasswordEvent($user)
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
