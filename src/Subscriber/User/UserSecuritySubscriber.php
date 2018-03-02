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

namespace App\Subscriber\User;

use App\Event\Interfaces\UserEventInterface;
use App\Event\User\UserCreatedEvent;
use App\Event\User\UserValidatedEvent;
use App\Subscriber\Interfaces\UserSecuritySubscriberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

/**
 * Class UserSecuritySubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSecuritySubscriber implements UserSecuritySubscriberInterface, EventSubscriberInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $emailSender;

    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * UserSecuritySubscriber constructor.
     *
     * @param Environment   $twig
     * @param string        $emailSender
     * @param \Swift_Mailer $swiftMailer
     */
    public function __construct(
        Environment $twig,
        string $emailSender,
        \Swift_Mailer $swiftMailer
    ) {
        $this->twig = $twig;
        $this->emailSender = $emailSender;
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::NAME => 'onUserCreated',
            UserValidatedEvent::NAME => 'onUserValidated'
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
}
