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

namespace App\Domain\Repository\Interfaces;

use App\Domain\Models\Interfaces\UserInterface;

/**
 * Interface UserRepositoryInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserRepositoryInterface
{
    /**
     * @param string $username
     * @param string $email
     *
     * @return UserInterface|null
     */
    public function getUserByUsernameAndEmail(string $username, string $email):? UserInterface;

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

    /**
     * @param string $token
     *
     * @return UserInterface|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserByToken(string $token):? UserInterface;
}
