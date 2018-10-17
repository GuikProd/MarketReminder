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

use App\Application\Messenger\Message\SessionMessage;
use App\Application\Messenger\Message\UserMessage;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Form\FormHandler\Security\Interfaces\ResetPasswordTypeHandlerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class ResetPasswordTypeHandler.
 *
 * @package App\UI\Form\FormHandler\Security
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ResetPasswordTypeHandler implements ResetPasswordTypeHandlerInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @inheritdoc
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        MessageBusInterface $messageBus,
        UserRepositoryInterface $userRepository
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->messageBus = $messageBus;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritdoc
     */
    public function handle(FormInterface $form, UserInterface $user): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->encoderFactory->getEncoder(User::class);

            $user->updatePassword( $encoder->encodePassword($form->getData()->password, null));

            $this->userRepository->flush();

            $this->messageBus->dispatch(new SessionMessage(
                'success',
                'user.notification.password_reset_success'
            ));

            $this->messageBus->dispatch(new UserMessage([
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail()
            ]));

            return true;
        }

        return false;
    }
}
