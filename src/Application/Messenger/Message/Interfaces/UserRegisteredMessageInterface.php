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

use App\Domain\Models\Interfaces\UserInterface;

/**
 * Interface UserRegisteredMessageInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserRegisteredMessageInterface
{
    /**
     * UserRegisteredMessageInterface constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = []);

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface;

    /**
     * @return string
     */
    public function getCreationDate(): string;
}
