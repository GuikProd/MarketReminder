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

use App\Models\Interfaces\UserInterface;

/**
 * Interface UserBuilderInterface
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserBuilderInterface
{
    /**
     * @return UserBuilderInterface
     */
    public function createUser(): UserBuilderInterface;

    /**
     * @param string $username
     *
     * @return UserBuilderInterface
     */
    public function withUsername(string $username): UserBuilderInterface;

    /**
     * @param string $email
     *
     * @return UserBuilderInterface
     */
    public function withEmail(string $email): UserBuilderInterface;

    /**
     * @param string $plainPassword
     *
     * @return UserBuilderInterface
     */
    public function withPlainPassword(string $plainPassword): UserBuilderInterface;

    /**
     * @param string $password
     *
     * @return UserBuilderInterface
     */
    public function withPassword(string $password): UserBuilderInterface;

    /**
     * @param string $role
     *
     * @return UserBuilderInterface
     */
    public function withRole(string $role): UserBuilderInterface;

    /**
     * @param \DateTime $creationDate
     *
     * @return UserBuilderInterface
     */
    public function withCreationDate(\DateTime $creationDate): UserBuilderInterface;

    /**
     * @param \DateTime $validationDate
     *
     * @return UserBuilderInterface
     */
    public function withValidationDate(\DateTime $validationDate): UserBuilderInterface;

    /**
     * @param bool $validated
     *
     * @return UserBuilderInterface
     */
    public function withValidated(bool $validated): UserBuilderInterface;

    /**
     * @param bool $active
     *
     * @return UserBuilderInterface
     */
    public function withActive(bool $active): UserBuilderInterface;

    /**
     * @param string $currentState
     *
     * @return UserBuilderInterface
     */
    public function withCurrentState(string $currentState): UserBuilderInterface;

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface;
}
