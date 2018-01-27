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

namespace App\Models\Interfaces;

/**
 * Interface UserInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserInterface
{
    /**
     * @return int|null
     */
    public function getId():? int;

    /**
     * @return null|string
     */
    public function getUsername():? string;

    /**
     * @param string $username
     */
    public function setUsername(string $username): void;

    /**
     * @return null|string
     */
    public function getEmail():? string;

    /**
     * @param string $email
     */
    public function setEmail(string $email): void;

    /**
     * @return null|string
     */
    public function getPlainPassword():? string;

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void;

    /**
     * @return null|string
     */
    public function getPassword():? string;

    /**
     * @param string $password
     */
    public function setPassword(string $password): void;

    /**
     * @return array
     */
    public function getRoles(): array;

    /**
     * @param string $role
     */
    public function setRole(string $role): void;

    /**
     * @return bool
     */
    public function getActive(): bool;

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void;

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate): void;

    /**
     * @return string
     */
    public function getCreationDate(): string;

    /**
     * @param \DateTime $validationDate
     */
    public function setValidationDate(\DateTime $validationDate): void;

    /**
     * @return null|string
     */
    public function getValidationDate():? string;

    /**
     * @param string $currentState
     */
    public function setCurrentState(string $currentState): void;

    /**
     * @return string
     */
    public function getCurrentState(): string;

    /**
     * @param bool $validated
     */
    public function setValidated(bool $validated): void;

    /**
     * @return bool
     */
    public function getValidated(): bool;
}
