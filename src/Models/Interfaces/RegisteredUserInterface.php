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
 * Interface RegisteredUserInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RegisteredUserInterface
{
    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @param string $username
     */
    public function setUsername(string $username): void;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string $email
     */
    public function setEmail(string $email): void;

    /**
     * @return string
     */
    public function getPlainPassword(): string;

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void;

    /**
     * @return string
     */
    public function getPassword(): string;

    /**
     * @param string $password
     */
    public function setPassword(string $password): void;

    /**
     * @return \SplFileInfo
     */
    public function getProfileImage(): \SplFileInfo;

    /**
     * @param \SplFileInfo $profileImage
     */
    public function setProfileImage(\SplFileInfo $profileImage): void;
}
