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

use App\Application\Event\Interfaces\SessionMessageEventInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface SessionMessageSubscriberInterface
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface SessionMessageSubscriberInterface
{
    /**
     * SessionMessageSubscriberInterface constructor.
     *
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator
    );

    /**
     * @param SessionMessageEventInterface $event
     */
    public function onSessionMessage(SessionMessageEventInterface $event): void;
}
