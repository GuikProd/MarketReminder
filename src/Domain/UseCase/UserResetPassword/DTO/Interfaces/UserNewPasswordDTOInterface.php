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
     * @param string|null $password  The new password (null if the form is invalid).
     */
    public function __construct(string $password = null);
}
