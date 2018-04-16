<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\UseCase\UserResetPassword\DTO\Interfaces;

/**
 * Interface UserNewPasswordDTOInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserNewPasswordDTOInterface
{
    /**
     * UserNewPasswordDTOInterface constructor.
     *
     * @param string  $password
     */
    public function __construct(string $password);
}
