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

namespace App\Application\Messenger\Message\Interfaces;

/**
 * Interface UserMessageInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserMessageInterface
{
    /**
     * UserMessageInterface constructor.
     *
     * @param array $payload The topic of the message.
     */
    public function __construct(array $payload);

    /**
     * @return array
     */
    public function getPayload(): array;
}
