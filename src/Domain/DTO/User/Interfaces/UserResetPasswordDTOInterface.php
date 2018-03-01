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

namespace App\Domain\DTO\User\Interfaces;

/**
 * Interface UserResetPasswordDTOInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserResetPasswordDTOInterface
{
    /**
     * Return the final reset token (based on username and email of the User).
     *
     * @return string
     */
    public function getResetToken(): string;
}
