<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Repository\Interfaces;

use App\Domain\Models\Interfaces\UserInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Interface UserRepositoryInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserRepositoryInterface
{
    /**
     * @param string $username
     * @param string $email
     *
     * @throws NonUniqueResultException
     *
     * @return UserInterface|null
     */
    public function getUserByUsernameAndEmail(string $username, string $email):? UserInterface;

    /**
     * @param string $username
     *
     * @throws NonUniqueResultException
     *
     * @return UserInterface|null
     */
    public function getUserByUsername(string $username):? UserInterface;

    /**
     * @param string $email
     *
     * @throws NonUniqueResultException
     *
     * @return UserInterface|null
     */
    public function getUserByEmail(string $email):? UserInterface;

    /**
     * @param string $token
     *
     * @throws NonUniqueResultException
     *
     * @return UserInterface|null
     */
    public function getUserByToken(string $token):? UserInterface;

    /**
     * @param string $token
     *
     * @throws NonUniqueResultException
     *
     * @return UserInterface|null
     */
    public function getUserByResetPasswordToken(string $token):? UserInterface;

    /**
     * @param UserInterface $user
     */
    public function save(UserInterface $user): void;

    /**
     * Allow to flush the last operations.
     */
    public function flush(): void;
}
