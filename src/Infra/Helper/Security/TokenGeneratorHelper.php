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

namespace App\Infra\Helper\Security;

use App\Infra\Helper\Security\Interfaces\TokenGeneratorHelperInterface;

/**
 * Class TokenGeneratorHelper
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TokenGeneratorHelper implements TokenGeneratorHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public static function generateResetPasswordToken(string $username, string $email): string
    {
        return substr(
            crypt(md5(str_rot13($username)), $email), 0, 10
        );
    }
}
