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

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserNewPasswordDTOInterface;

/**
 * Class UserNewPasswordDTO.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserNewPasswordDTO implements UserNewPasswordDTOInterface
{
    /**
     * @var string
     */
    public $password;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $password = null)
    {
        $this->password = $password;
    }
}
