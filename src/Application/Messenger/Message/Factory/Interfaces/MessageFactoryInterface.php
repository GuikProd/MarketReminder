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

namespace App\Application\Messenger\Message\Factory\Interfaces;

use App\Application\Messenger\Message\Interfaces\UserMessageInterface;

/**
 * Interface MessageFactoryInterface.
 *
 * @package App\Application\Messenger\Message\Factory\Interfaces
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface MessageFactoryInterface
{
    /**
     * @param array  $payload  The payload of the message.
     *
     * @return UserMessageInterface The actual message.
     */
    public function createUserMessage(array $payload = []): UserMessageInterface;

    /**
     * @param \ArrayAccess $resolver
     *
     * @return void
     */
    public function checkMessagePayload(\ArrayAccess $resolver): void;
}
