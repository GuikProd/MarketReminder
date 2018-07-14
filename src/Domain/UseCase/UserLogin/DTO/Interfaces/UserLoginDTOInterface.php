<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@hotmail.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\UseCase\UserLogin\DTO\Interfaces;

/**
 * Interface UserLoginDTOInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@hotmail.fr>
 */
interface UserLoginDTOInterface
{
    /**
     * UserLoginDTOInterface constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password);
}
