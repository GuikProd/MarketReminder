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

namespace App\Application\Messenger\Handler\Interfaces;

use App\Application\Messenger\Message\UserMessage;
use App\Infra\Mailer\Interfaces\MailFactoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Twig\Environment;

/**
 * Interface UserHandlerInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserHandlerInterface
{
    /**
     * UserHandlerInterface constructor.
     *
     * @param MailFactoryInterface $mailFactory
     * @param \Swift_Mailer        $swiftMailer
     * @param Environment          $twig
     * @param PresenterInterface   $presenter
     */
    public function __construct(
        MailFactoryInterface $mailFactory,
        \Swift_Mailer $swiftMailer,
        Environment $twig,
        PresenterInterface $presenter
    );

    /**
     * @param UserMessage $message
     *
     * @return void
     */
    public function __invoke(UserMessage $message): void;

    /**
     * @param array $payload
     *
     * @return void
     */
    public function sendRegistrationMail(array $payload = []): void;

    /**
     * @param array $payload
     *
     * @return void
     */
    public function sendAskResetPasswordMail(array $payload = []): void;

    /**
     * @param array $payload
     *
     * @return void
     */
    public function sendResetPasswordMail(array $payload = []): void;

    /**
     * @param array $payload
     *
     * @return void
     */
    public function sendUserValidatedMail(array $payload = []): void;
}
