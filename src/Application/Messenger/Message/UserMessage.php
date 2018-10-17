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

use App\Application\Messenger\Message\Interfaces\UserMessageInterface;

/**
 * Class UserMessage.
 *
 * @package App\Application\Messenger\Message
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserMessage implements UserMessageInterface
{
    /**
     * @var array
     */
    private $payload = [];

    /**
     * @inheritdoc
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @inheritdoc
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
