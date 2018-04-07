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

namespace App\Application\Event\Interfaces;

/**
 * Interface SessionMessageEventInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface SessionMessageEventInterface
{
    const NAME = 'session.message_added';

    /**
     * SessionMessageEventInterface constructor.
     *
     * @param string  $flashBag
     * @param string  $message
     */
    public function __construct(
        string $flashBag,
        string $message
    );

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return string
     */
    public function getFlashBag(): string;
}