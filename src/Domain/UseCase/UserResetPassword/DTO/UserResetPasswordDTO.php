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

namespace App\Domain\UseCase\UserResetPassword\DTO;

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserResetPasswordDTOInterface;

/**
 * Class UserResetPasswordDTO
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserResetPasswordDTO implements UserResetPasswordDTOInterface
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $username;

    /**
     * UserResetPasswordDTO constructor.
     *
     * @param string $email    The email of the User who reset his password.
     * @param string $username The username of the User who reset his password.
     */
    public function __construct(
        string $email,
        string $username
    ) {
        $this->email = $email;
        $this->username = $username;
    }
}
