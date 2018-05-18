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

use App\Application\Event\UserEvent;
use App\Application\Subscriber\Interfaces\UserSubscriberInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class UserSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class UserSubscriber implements EventSubscriberInterface, UserSubscriberInterface
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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $emailSender,
        \Swift_Mailer $swiftMailer,
        TranslatorInterface $translator,
        Environment $twig,
        PresenterInterface $presenter
    ) {
        $this->emailSender = $emailSender;
        $this->swiftMailer = $swiftMailer;
        $this->translator = $translator;
        $this->twig = $twig;
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvent::USER_ASK_RESET_PASSWORD => 'onUserAskResetPasswordEvent',
            UserEvent::USER_CREATED => 'onUserCreated',
            UserEvent::USER_RESET_PASSWORD => 'onUserResetPassword',
            UserEvent::USER_VALIDATED => 'onUserValidated'
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserAskResetPasswordEvent(UserEvent $event): void
    {
        $this->presenter->prepareOptions([
            'email' => [
                'content' => [
                    'text' => 'user.ask_reset_password.content',
                    'link' => [
                        'text' => 'user.ask_reset_password.link.text'
                    ]
                ],
                'header' => 'user.ask_reset_password.header',
                'subject' => 'user.ask_reset_password.header'
            ],
            'user' => $event->getUser()
        ]);

        $askResetPasswordMail = (new \Swift_Message)
            ->setSubject(
                $this->translator
                     ->trans($this->presenter->getEmail()['subject'])
            )
            ->setFrom($this->emailSender)
            ->setTo($this->presenter->getUser()->getEmail())
            ->setBody(
                $this->twig->render('emails/security/user_ask_reset_password.html.twig', [
                    'presenter' => $this->presenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($askResetPasswordMail);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserCreated(UserEvent $event): void
    {
        $this->presenter->prepareOptions([
            'email' => [
                'content' => [
                    'first_part' => 'user.registration.welcome.content_first_part',
                    'second_part' => 'user.registration.welcome.content_second_part',
                    'link' => [
                        'text' => 'user.registration.welcome.content.link.text'
                    ]
                ],
                'header' => 'user.registration.welcome.header',
                'subject' => 'user.registration.welcome.header'
            ],
            'user' => $event->getUser()
        ]);

        $registrationMail = (new \Swift_Message)
            ->setSubject(
                $this->translator
                     ->trans($this->presenter->getEmail()['subject'])
            )
            ->setFrom($this->emailSender)
            ->setTo($this->presenter->getUser()->getEmail())
            ->setBody(
                $this->twig->render('emails/security/registration_mail.html.twig', [
                    'presenter' => $this->presenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($registrationMail);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserResetPassword(UserEvent $event): void
    {
        $this->presenter->prepareOptions([
            '_locale' => '',
            'email' => [
                'content' => [
                    'text' => 'user.reset_password.content',
                    'link' => [
                        'text' => 'security.login'
                    ]
                ],
                'header' => 'user.reset_password.header',
                'subject' => 'user.reset_password.header'
            ],
            'user' => $event->getUser()
        ]);

        $resetPasswordMessage = (new \Swift_Message)
            ->setSubject(
                $this->translator
                     ->trans($this->presenter->getEmail()['subject'])
            )
            ->setFrom($this->emailSender)
            ->setTo($this->presenter->getUser()->getEmail())
            ->setBody(
                $this->twig->render('emails/security/user_reset_password.html.twig', [
                    'presenter' => $this->presenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($resetPasswordMessage);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserValidated(UserEvent $event): void
    {
        $this->presenter->prepareOptions([
            'email' => [
                'content' => [

                ],
                'header' => '',
                'subject' => ''
            ],
            'user' => $event->getUser()
        ]);

        $validationMail = (new \Swift_Message)
            ->setSubject(
                $this->translator
                     ->trans($this->presenter->getEmail()['subject'])
            )
            ->setFrom($this->emailSender)
            ->setTo($this->presenter->getUser()->getEmail())
            ->setBody(
                $this->twig->render('emails/security/validation_mail.html.twig', [
                    'presenter' => $this->presenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($validationMail);
    }
}
