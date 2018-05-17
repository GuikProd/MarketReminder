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
use App\Application\Event\User\Interfaces\UserResetPasswordEventInterface;
use App\Application\Event\User\UserResetPasswordEvent;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Form\FormHandler\Interfaces\ResetPasswordTypeHandlerInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class ResetPasswordTypeHandler.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordTypeHandler implements ResetPasswordTypeHandlerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EncoderFactoryInterface $encoderFactory,
        PresenterInterface $resetPasswordPresenter,
        UserRepositoryInterface $userRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->encoderFactory = $encoderFactory;
        $this->presenter = $resetPasswordPresenter;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $form, UserInterface $user): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->encoderFactory->getEncoder(User::class);
            $passwordEncoder = \Closure::fromCallable([$encoder, 'encodePassword']);

            $user->updatePassword($passwordEncoder($form->getData()->password, null));

            $this->userRepository->flush();

            $this->presenter->prepareOptions([
                'notification' => [
                    'content' => 'user.notification.password_reset_success',
                    'title' => 'user.notification.password_reset.header'
                ]
            ]);

            $this->eventDispatcher->dispatch(
                SessionMessageEvent::NAME,
                new SessionMessageEvent(
                    'success',
                    $this->presenter->getNotificationMessage()['content']
                )
            );

            $this->eventDispatcher->dispatch(
                UserResetPasswordEventInterface::NAME,
                new UserResetPasswordEvent($user)
            );

            return true;
        }

        return false;
    }
}
