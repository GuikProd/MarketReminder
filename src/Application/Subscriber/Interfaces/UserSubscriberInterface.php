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

namespace App\Application\Subscriber\Interfaces;

use App\Application\Event\User\Interfaces\UserAskResetPasswordEventInterface;
use App\Application\Event\User\Interfaces\UserCreatedEventInterface;
use App\Application\Event\User\Interfaces\UserResetPasswordEventInterface;
use App\Application\Event\User\Interfaces\UserValidatedEventInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Interface UserSubscriberInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserSubscriberInterface
{
    /**
     * UserSubscriberInterface constructor.
     *
     * @param string              $emailSender
     * @param \Swift_Mailer       $swiftMailer
     * @param TranslatorInterface $translator
     * @param Environment         $twig
     */
    public function __construct(
        string $emailSender,
        \Swift_Mailer $swiftMailer,
        TranslatorInterface $translator,
        Environment $twig
    );

    /**
     * @param UserAskResetPasswordEventInterface $event
     */
    public function onUserAskResetPasswordEvent(UserAskResetPasswordEventInterface $event): void;

    /**
     * @param UserCreatedEventInterface  $event
     */
    public function onUserCreated(UserCreatedEventInterface $event): void;

    /**
     * @param UserResetPasswordEventInterface $event
     */
    public function onUserResetPassword(UserResetPasswordEventInterface $event): void;

    /**
     * @param UserValidatedEventInterface  $event
     */
    public function onUserValidated(UserValidatedEventInterface $event): void;
}
