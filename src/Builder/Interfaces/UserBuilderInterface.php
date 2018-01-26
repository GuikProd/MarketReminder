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

namespace App\Builder\Interfaces;

use App\Models\Interfaces\RegisteredUserInterface;

/**
 * Interface UserBuilderInterface
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserBuilderInterface
{
    /**
     * @param RegisteredUserInterface $registeredUser
     * 
     * @return UserBuilderInterface
     */
    public function registerUser(RegisteredUserInterface $registeredUser): UserBuilderInterface;
}