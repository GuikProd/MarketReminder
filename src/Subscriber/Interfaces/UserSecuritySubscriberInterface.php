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

use App\Event\Interfaces\UserEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Interface UserSecuritySubscriberInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserSecuritySubscriberInterface extends EventSubscriberInterface
{
    /**
     * Allow to send a mail linked to the account creation,
     * this mail contain the validation token used in order to terminate the process.
     *
     * @param UserEventInterface    $event
     *
     * @throws \Twig_Error_Loader   @see Environment
     * @throws \Twig_Error_Runtime  @see Environment
     * @throws \Twig_Error_Syntax   @see Environment
     */
    public function onUserCreated(UserEventInterface $event): void;

    /**
     * Allow to validated the user account and send an email in order
     * to invite the user to start his journey.
     *
     * @param UserEventInterface $event
     */
    public function onUserValidated(UserEventInterface $event): void;
}
