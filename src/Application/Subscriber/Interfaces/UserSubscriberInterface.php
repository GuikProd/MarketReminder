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

namespace App\Application\Subscriber\Interfaces;

use App\Application\Event\UserEvent;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * Interface UserSubscriberInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserSubscriberInterface
{
    /**
     * UserSubscriberInterface constructor.
     *
     * @param string               $emailSender
     * @param \Swift_Mailer        $swiftMailer
     * @param Environment          $twig
     * @param PresenterInterface   $presenter
     * @param RequestStack         $requestStack
     */
    public function __construct(
        string $emailSender,
        \Swift_Mailer $swiftMailer,
        Environment $twig,
        PresenterInterface $presenter,
        RequestStack $requestStack
    );

    /**
     * @param UserEvent $event
     */
    public function onUserAskResetPasswordEvent(UserEvent $event): void;

    /**
     * @param UserEvent $event
     */
    public function onUserCreated(UserEvent $event): void;

    /**
     * @param UserEvent $event
     */
    public function onUserResetPassword(UserEvent $event): void;

    /**
     * @param UserEvent $event
     */
    public function onUserValidated(UserEvent $event): void;
}
