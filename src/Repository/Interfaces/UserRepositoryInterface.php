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
 * Interface UserRepositoryInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserRepositoryInterface
{
    /**
     * @param string $username
     *
     * @return array|null
     */
    public function getUserByUsername(string $username):? array;

    /**
     * @param string $email
     *
     * @return array|null
     */
    public function getUserByEmail(string $email):? array;

    /**
     * @param string $token
     *
     * @return UserInterface|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserByToken(string $token):? UserInterface;
}
