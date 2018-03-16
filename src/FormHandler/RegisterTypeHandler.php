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

namespace App\FormHandler;

use App\Application\Symfony\Events\SessionMessageEvent;
use App\Domain\Event\User\UserCreatedEvent;
use App\Domain\Models\User;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * RegisterTypeHandler constructor.
     *
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $registerForm): bool
    {
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            $user = new User(
                $registerForm->getData()->email,
                $registerForm->getData()->username,
                $registerForm->getData()->password,
                $registerForm->getData()->validationToken,
                $registerForm->getData()->profileImage
            );

            $errors = $this->validator->validate($user, null, 'registration');

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
