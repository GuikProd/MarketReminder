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

namespace App\Application\Symfony\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class SessionMessageEvent.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class SessionMessageEvent extends Event
{
    const NAME = 'session.message_added';

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $flashBag;

    /**
     * SessionMessageEvent constructor.
     *
     * @param string  $flashBag
     * @param string  $message
     */
    public function __construct(
        string $flashBag,
        string $message
    ) {
        $this->flashBag = $flashBag;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getFlashBag(): string
    {
        return $this->flashBag;
    }
}
