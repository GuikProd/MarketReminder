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

namespace App\Application\Symfony\Subscriber\Interfaces;

use App\Application\Symfony\Events\SessionMessageEvent;

/**
 * Interface SessionMessageSubscriberInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface SessionMessageSubscriberInterface
{
    /**
     * @param SessionMessageEvent $event
     */
    public function onSessionMessage(SessionMessageEvent $event): void;
}
