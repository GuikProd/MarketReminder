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

use App\Event\User\UserCreatedEvent;
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
     * @param UserCreatedEvent $event
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserCreated(UserCreatedEvent $event): void;
}
