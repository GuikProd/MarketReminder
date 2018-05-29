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

namespace App\Application\Event;

use App\Application\Event\Interfaces\SessionMessageEventInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class SessionMessageEvent.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class SessionMessageEvent extends Event implements SessionMessageEventInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $flashBag;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $flashBag,
        string $message
    ) {
        $this->flashBag = $flashBag;
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getFlashBag(): string
    {
        return $this->flashBag;
    }
}
