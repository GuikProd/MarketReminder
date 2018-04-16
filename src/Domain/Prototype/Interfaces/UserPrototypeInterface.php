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

namespace App\Domain\Prototype\Interfaces;

use App\Domain\Models\Interfaces\UserInterface;

/**
 * Interface UserPrototypeInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserPrototypeInterface
{
    /**
     * @param $existingUser  UserInterface
     *
     * @return UserPrototypeInterface
     */
    public function createFromUser(UserInterface $existingUser): UserPrototypeInterface;

    /**
     * @return UserInterface
     */
    public function getPrototype(): UserInterface;
}
