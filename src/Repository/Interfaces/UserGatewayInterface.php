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

namespace App\Repository\Interfaces;

use App\Models\Interfaces\UserInterface;

/**
 * Interface UserGatewayInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserGatewayInterface
{
    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function getUserByUsername(string $username):? UserInterface;

    /**
     * @param string $email
     *
     * @return UserInterface|null
     */
    public function getUserByEmail(string $email):? UserInterface;
}
