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

namespace App\Application\Subscriber;

use App\Application\Event\User\Interfaces\UserCreatedEventInterface;
use App\Application\Event\User\Interfaces\UserResetPasswordEventInterface;
use App\Application\Event\User\Interfaces\UserValidatedEventInterface;
use App\Application\Subscriber\Interfaces\UserSubscriberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

/**
 * Class UserSubscriber
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSubscriber implements EventSubscriberInterface, UserSubscriberInterface
{
    /**
     * @var string
     */
    private $emailSender;

    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $emailSender,
        \Swift_Mailer $swiftMailer,
        Environment $twig
    ) {
        $this->emailSender = $emailSender;
        $this->swiftMailer = $swiftMailer;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEventInterface::NAME => 'onUserCreated',
            UserValidatedEventInterface::NAME => 'onUserValidated',
            UserResetPasswordEventInterface::NAME => 'onUserResetPassword'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onUserCreated(UserCreatedEventInterface $event): void
    {
        $registrationMail =  (new \Swift_Message)
            ->setFrom($this->emailSender)
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->twig
                    ->render('emails/security/registration_mail.html.twig', [
                        'user' => $event->getUser(),
                    ]),
                'text/html'
            );

        $this->swiftMailer->send($registrationMail);
    }

    /**
     * {@inheritdoc}
     */
    public function onUserValidated(UserValidatedEventInterface $event): void
    {
        $validationMail =  (new \Swift_Message)
            ->setFrom($this->emailSender)
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->twig
                    ->render('emails/security/validation_mail.html.twig', [
                        'user' => $event->getUser(),
                    ]),
                'text/html'
            );

        $this->swiftMailer->send($validationMail);
    }

    /**
     * {@inheritdoc}
     */
    public function onUserResetPassword(UserResetPasswordEventInterface $event): void
    {
        $message = (new \Swift_Message)
                    ->setFrom($this->emailSender)
                    ->setTo($event->getUser()->getEmail())
                    ->setBody(
                        $this->twig->render('emails/security/user_reset_password.html.twig', [
                            'user' => $event->getUser()
                        ])
                    );

        $this->swiftMailer->send($message);
    }
}
