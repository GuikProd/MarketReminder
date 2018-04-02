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

use App\Application\Symfony\Events\SessionMessageEvent;
use App\Domain\Event\User\UserCreatedEvent;
use App\Domain\Models\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Form\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterTypeHandler.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandler implements RegisterTypeHandlerInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EncoderFactoryInterface
     */
    private $passwordEncoderFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher,
        EncoderFactoryInterface $passwordEncoderFactory
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordEncoderFactory = $passwordEncoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $registerForm): bool
    {
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            $encoder = $this->passwordEncoderFactory->getEncoder(User::class);

            $user = new User(
                $registerForm->getData()->email,
                $registerForm->getData()->username,
                $registerForm->getData()->password,
                \Closure::fromCallable([$encoder, 'encodePassword']),
                $registerForm->getData()->validationToken,
                !is_array($registerForm->get('profileImage')->getData())
                    ? $registerForm->get('profileImage')->getData()
                    : null
            );

            $errors = $this->validator->validate($user, null, ['User', 'registration']);

            if (count($errors) > 0) {
                $this->eventDispatcher->dispatch(
                    SessionMessageEvent::NAME,
                    new SessionMessageEvent(
                        'failure',
                        'user.invalid_credentials'
                    )
                );

                return false;
            }

            $this->userRepository->save($user);

            $this->eventDispatcher->dispatch(
                UserCreatedEvent::NAME,
                new UserCreatedEvent($user)
            );

            $this->eventDispatcher->dispatch(
                SessionMessageEvent::NAME,
                new SessionMessageEvent(
                    'success',
                    'user.account_created'
                )
            );

            return true;
        }

        return false;
    }
}
