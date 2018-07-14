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

namespace App\Domain\UseCase\UserLogin\DTO;

use App\Domain\UseCase\UserLogin\DTO\Interfaces\UserLoginDTOInterface;

/**
 * Class UserLoginDTO.
 *
 * @author Guillaume Loulier <guillaume.loulier@hotmail.fr>
 */
final class UserLoginDTO implements UserLoginDTOInterface
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
