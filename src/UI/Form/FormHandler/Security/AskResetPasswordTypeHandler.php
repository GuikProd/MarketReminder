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

namespace App\UI\Form\FormHandler\Security;

use App\Application\Helper\Security\TokenGeneratorHelper;
use App\Application\Messenger\Message\SessionMessage;
use App\Application\Messenger\Message\UserMessage;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use App\UI\Form\FormHandler\Security\Interfaces\AskResetPasswordTypeHandlerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AskResetPasswordTypeHandler.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class AskResetPasswordTypeHandler implements AskResetPasswordTypeHandlerInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        MessageBusInterface $messageBus,
        UserRepositoryInterface $userRepository
    ) {
        $this->messageBus = $messageBus;
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

            $this->messageBus->dispatch(new UserMessage([
                '_topic' => 'reset_password',
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'reset_token' => $user->getResetPasswordToken()
            ]));

            $this->messageBus->dispatch(new SessionMessage(
                'success', 'user.reset_password.success'
            ));

            return true;
        }

        return false;
    }
}
