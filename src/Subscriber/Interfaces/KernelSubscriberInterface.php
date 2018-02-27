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

namespace App\Subscriber\Interfaces;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Interface KernelSubscriberInterface.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface KernelSubscriberInterface
{
    /**
     * Allow to query the user depending on the actual route and the "token"
     * attribute passed via the Request object.
     *
     * If no user is found or if the user is already validated, a flash message is returned
     * and the Response is send to "index".
     *
     * If any other case, the user is passed into the ParameterBag of the Request
     * in order to help the Action to validate it.
     *
     * @param GetResponseEvent $event
     */
    public function onUserValidation(GetResponseEvent $event): void;

    /**
     * @param GetResponseEvent $event
     */
    public function onUserAskResetPassword(GetResponseEvent $event): void;
}
