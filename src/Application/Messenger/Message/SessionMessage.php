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

namespace App\Application\Messenger\Message;

use App\Application\Messenger\Message\Interfaces\SessionMessageInterface;

/**
 * Class SessionMessage.
 *
 * @package App\Application\Messenger\Message
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class SessionMessage implements SessionMessageInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $message;

    /**
     * @inheritdoc
     */
    public function __construct(
        string $key,
        string $message
    ) {
        $this->key = $key;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @inheritdoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
