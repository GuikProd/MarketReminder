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

namespace App\Domain\Subscriber;

use App\Domain\Event\Interfaces\UserEventInterface;
use App\Domain\Event\User\UserCreatedEvent;
use App\Domain\Event\User\UserResetPasswordEvent;
use App\Domain\Event\User\UserValidatedEvent;
use App\Domain\Subscriber\Interfaces\UserSubscriberInterface;
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
            UserCreatedEvent::NAME => 'onUserCreated',
            UserValidatedEvent::NAME => 'onUserValidated',
            UserResetPasswordEvent::NAME => 'onUserResetPassword'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onUserCreated(UserEventInterface $event): void
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

        $this->swiftMailer
            ->send($registrationMail);
    }

    /**
     * {@inheritdoc}
     */
    public function onUserValidated(UserEventInterface $event): void
    {
        $validationMail =  (new \Swift_Message)
            ->setFrom($this->emailSender)
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->twig
                    ->render('emails/security/registration_mail.html.twig', [
                        'user' => $event->getUser(),
                    ]),
                'text/html'
            );

        $this->swiftMailer
            ->send($validationMail);
    }

    /**
     * {@inheritdoc}
     */
    public function onUserResetPassword(UserEventInterface $event): void
    {
        $message = (new \Swift_Message)
                    ->setFrom($this->emailSender)
                    ->setTo($event->getUser()->getEmail())
                    ->setBody(
                        $this->twig->render('emails/security/user_resetPassword.html.twig')
                    );

        $this->swiftMailer->send($message);
    }
}
