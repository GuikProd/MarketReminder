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

use App\Application\Event\User\Interfaces\UserAskResetPasswordEventInterface;
use App\Application\Event\User\Interfaces\UserCreatedEventInterface;
use App\Application\Event\User\Interfaces\UserResetPasswordEventInterface;
use App\Application\Event\User\Interfaces\UserValidatedEventInterface;
use App\Application\Subscriber\Interfaces\UserSubscriberInterface;
use App\UI\Presenter\User\Interfaces\UserEmailPresenterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class UserSubscriber.
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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UserEmailPresenterInterface
     */
    private $userEmailPresenter;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $emailSender,
        \Swift_Mailer $swiftMailer,
        TranslatorInterface $translator,
        Environment $twig,
        UserEmailPresenterInterface $presenter
    ) {
        $this->emailSender = $emailSender;
        $this->swiftMailer = $swiftMailer;
        $this->translator = $translator;
        $this->twig = $twig;
        $this->userEmailPresenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserAskResetPasswordEventInterface::NAME => 'onUserAskResetPasswordEvent',
            UserCreatedEventInterface::NAME => 'onUserCreated',
            UserValidatedEventInterface::NAME => 'onUserValidated',
            UserResetPasswordEventInterface::NAME => 'onUserResetPassword'
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserAskResetPasswordEvent(UserAskResetPasswordEventInterface $event): void
    {
        $this->userEmailPresenter->prepareOptions([
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
                     ->trans($this->userEmailPresenter->getEmail()['subject'])
            )
            ->setFrom($this->emailSender)
            ->setTo($this->userEmailPresenter->getUser()->getEmail())
            ->setBody(
                $this->twig->render('emails/security/user_ask_reset_password.html.twig', [
                    'presenter' => $this->userEmailPresenter
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
    public function onUserCreated(UserCreatedEventInterface $event): void
    {
        $this->userEmailPresenter->prepareOptions([
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
                     ->trans($this->userEmailPresenter->getEmail()['subject'])
            )
            ->setFrom($this->emailSender)
            ->setTo($this->userEmailPresenter->getUser()->getEmail())
            ->setBody(
                $this->twig->render('emails/security/registration_mail.html.twig', [
                    'presenter' => $this->userEmailPresenter
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
    public function onUserResetPassword(UserResetPasswordEventInterface $event): void
    {
        $this->userEmailPresenter->prepareOptions([
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
                     ->trans($this->userEmailPresenter->getEmail()['subject'])
            )
            ->setFrom($this->emailSender)
            ->setTo($this->userEmailPresenter->getUser()->getEmail())
            ->setBody(
                $this->twig->render('emails/security/user_reset_password.html.twig', [
                    'presenter' => $this->userEmailPresenter
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
    public function onUserValidated(UserValidatedEventInterface $event): void
    {
        $this->userEmailPresenter->prepareOptions([
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
                     ->trans($this->userEmailPresenter->getEmail()['subject'])
            )
            ->setFrom($this->emailSender)
            ->setTo($this->userEmailPresenter->getUser()->getEmail())
            ->setBody(
                $this->twig->render('emails/security/validation_mail.html.twig', [
                    'presenter' => $this->userEmailPresenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($validationMail);
    }
}
