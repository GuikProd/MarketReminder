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

namespace App\Helper\Interfaces\User;

use App\Models\Interfaces\UserInterface;

/**
 * Interface UserValidatorHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserValidatorHelperInterface
{
    /**
     * Validate the user account.
     *
     * @param UserInterface $user
     */
    public static function validate(UserInterface $user): void;
}
