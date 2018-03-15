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

namespace App\Domain\Subscriber\Interfaces;

use App\Domain\Event\User\UserResetPasswordEvent;
use Twig\Environment;

/**
 * Interface UserSubscriberInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserSubscriberInterface
{
    /**
     * UserSubscriberInterface constructor.
     *
     * @param string        $emailSender
     * @param \Swift_Mailer $swiftMailer
     * @param Environment   $twig
     */
    public function __construct(string $emailSender, \Swift_Mailer $swiftMailer, Environment $twig);

    /**
     * This method allow to send the email which contain the reset password token.
     *
     * @param UserResetPasswordEvent $event
     */
    public function onUserResetPassword(UserResetPasswordEvent $event): void;
}
