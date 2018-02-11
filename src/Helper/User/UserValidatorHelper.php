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

namespace App\Helper\User;

use App\Models\Interfaces\UserInterface;
use App\Helper\Interfaces\User\UserValidatorHelperInterface;

/**
 * Class UserValidatorHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class UserValidatorHelper implements UserValidatorHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public static function validate(UserInterface $user): void
    {
        $user->setValidated(true);
        $user->setValidationToken('');
        $user->setValidationDate(new \DateTime());
    }
}
