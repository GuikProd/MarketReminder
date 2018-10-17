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

namespace App\Application\Messenger\Handler;

use App\Application\Messenger\Handler\Interfaces\UserHandlerInterface;
use App\Application\Messenger\Message\UserMessage;
use App\Infra\Mailer\Interfaces\MailFactoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Twig\Environment;

/**
 * Class UserMessageHandler.
 *
 * @package App\Application\Messenger\Handler
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserMessageHandler implements UserHandlerInterface
{
    /**
     * @var MailFactoryInterface
     */
    private $mailFactory;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @inheritdoc
     */
    public function __construct(
        MailFactoryInterface $mailFactory,
        \Swift_Mailer $swiftMailer,
        Environment $twig,
        PresenterInterface $presenter
    ) {
        $this->mailFactory = $mailFactory;
        $this->mailer = $swiftMailer;
        $this->twig = $twig;
        $this->presenter = $presenter;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(UserMessage $message): void
    {
        switch ($message->getPayload()['_topic']) {
            case 'registration':
                $this->sendRegistrationMail($message->getPayload());
                break;
            case 'ask_reset_password':
                $this->sendAskResetPasswordMail($message->getPayload());
                break;
            case 'reset_password':
                $this->sendResetPasswordMail($message->getPayload());
                break;
            case 'validation':
                $this->sendUserValidatedMail($message->getPayload());
                break;
            default:
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function sendRegistrationMail(array $payload = []): void
    {
        $this->presenter->prepareOptions([
            '_locale' => $payload['_locale'],
            'content' => [],
            'page' => [
                'content_first' => [
                    'key' => 'user.registration.welcome.content_first_part',
                    'channel' => 'mail'
                ],
                'content_second' => [
                    'key' => 'user.registration.welcome.content_second_part',
                    'channel' => 'mail'
                ],
                'link' => [
                    'key' => 'user.registration.welcome.content.link.text',
                    'channel' => 'mail'
                ],
                'header' => [
                    'key' => 'user.registration.welcome.header',
                    'channel' => 'mail'
                ],
                'subject' => [
                    'key' => 'user.registration.welcome.header',
                    'channel' => 'mail'
                ]
            ],
            'user' => $payload['user'],
        ]);

        $mail = $this->mailFactory->createMail([
            'receiver' => $this->presenter->getViewOptions()['user']['email'],
            'subject' => $this->presenter->getPage()['subject']['value'],
            'body' => $this->twig->render('emails/security/registration_mail.html.twig', [
                'presenter' => $this->presenter
            ])
        ]);

        $this->mailer->send($mail);
    }

    /**
     * @inheritdoc
     */
    public function sendAskResetPasswordMail(array $payload = []): void
    {
        $this->presenter->prepareOptions([
            '_locale' => $payload['_locale'],
            'content' => [],
            'page' => [
                'content' => [
                    'key' => 'user.ask_reset_password.content',
                    'channel' => 'mail'
                ],
                'link' => [
                    'key' => 'user.ask_reset_password.link.text',
                    'channel' => 'mail'
                ],
                'header' => [
                    'key' => 'user.ask_reset_password.header',
                    'channel' => 'mail'
                ],
                'subject' => [
                    'key' => 'user.ask_reset_password.header',
                    'channel' => 'mail'
                ]
            ],
            'user' => $payload['user'],
        ]);

        $mail = $this->mailFactory->createMail([
            'receiver' => $this->presenter->getViewOptions()['user']['email'],
            'subject' => $this->presenter->getPage()['subject']['value'],
            'body' => $this->twig->render('emails/security/user_ask_reset_password.html.twig', [
                'presenter' => $this->presenter
            ])
        ]);

        $this->mailer->send($mail);
    }

    /**
     * @inheritdoc
     */
    public function sendResetPasswordMail(array $payload = []): void
    {
        $this->presenter->prepareOptions([
            '_locale' => $payload['_locale'],
            'content' => [],
            'page' => [
                'body' => [
                    'key' => 'user.reset_password.content',
                    'channel' => 'mail'
                ],
                'link' => [
                    'key' => 'user.reset_password.link',
                    'channel' => 'mail'
                ],
                'header' => [
                    'key' => 'user.reset_password.header',
                    'channel' => 'mail',
                ],
                'subject' => [
                    'key' => 'user.reset_password.header',
                    'channel' => 'mail'
                ]
            ],
            'user' => $payload['user'],
        ]);

        $mail = $this->mailFactory->createMail([
            'receiver' => $this->presenter->getViewOptions()['user']['email'],
            'subject' => $this->presenter->getPage()['subject']['value'],
            'body' => $this->twig->render('emails/security/user_reset_password.html.twig', [
                'presenter' => $this->presenter
            ])
        ]);

        $this->mailer->send($mail);
    }

    /**
     * @inheritdoc
     */
    public function sendUserValidatedMail(array $payload = []): void
    {
        $this->presenter->prepareOptions([
            '_locale' => $payload['_locale'],
            'content' => [],
            'page' => [
                'subject' => [
                    'key' => 'user.validation.subject',
                    'channel' => 'mail'
                ],
                'content' => [
                    'key' => 'user.validation.header',
                    'channel' => 'mail'
                ],
                'footer' => [
                    'key' => 'user.validation.footer',
                    'channel' => 'mail'
                ],
                'dashboard' => [
                    'key' => 'user.validation.content.link',
                    'channel' => 'mail'
                ],
                'contact' => [
                    'key' => 'user.validation.content.contact',
                    'channel' => 'mail'
                ]
            ],
            'user' => $payload['user'],
        ]);

        $mail = $this->mailFactory->createMail([
            'receiver' => $this->presenter->getViewOptions()['user']['email'],
            'subject' => $this->presenter->getPage()['subject']['value'],
            'body' => $this->twig->render('emails/security/validation_mail.html.twig', [
                'presenter' => $this->presenter
            ])
        ]);

        $this->mailer->send($mail);
    }
}
