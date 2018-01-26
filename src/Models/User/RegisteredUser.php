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

namespace App\Models\User;

use App\Models\Interfaces\RegisteredUserInterface;

/**
 * Class RegisteredUser
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisteredUser implements RegisteredUserInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \SplFileInfo
     */
    private $profileImage;

    /**
     * RegisteredUser constructor.
     *
     * @param string $username
     * @param string $email
     * @param string $plainPassword
     * @param \SplFileInfo|null $profileImage
     */
    public function __construct(
        string $username,
        string $email,
        string $plainPassword,
        \SplFileInfo $profileImage = null
    ) {
        $this->email = $email;
        $this->username = $username;
        $this->profileImage = $profileImage;
        $this->plainPassword = $plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileImage(): \SplFileInfo
    {
        return $this->profileImage;
    }

    /**
     * {@inheritdoc}
     */
    public function setProfileImage(\SplFileInfo $profileImage): void
    {
        $this->profileImage = $profileImage;
    }
}
