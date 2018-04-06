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

namespace App\Application\Helper\Security\Interfaces;

/**
 * Interface TokenGeneratorHelperInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface TokenGeneratorHelperInterface
{
    /**
     * Return a random string used for resetting the user password.
     *
     * @param string  $username  The username of the user which ask for the reset.
     * @param string  $email     The email of the user which ask for the reset.s
     *
     * @return string  The reset token.
     */
    public static function generateResetPasswordToken(string $username, string $email): string;
}
