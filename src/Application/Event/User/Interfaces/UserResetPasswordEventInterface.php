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

namespace App\Application\Event\User\Interfaces;

use App\Domain\Models\Interfaces\UserInterface;

/**
 * Interface UserResetPasswordEventInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserResetPasswordEventInterface
{
    const NAME = 'user.password_reset';

    /**
     * UserResetPasswordEventInterface constructor.
     *
     * @param UserInterface  $user
     */
    public function __construct(UserInterface $user);

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface;
}
