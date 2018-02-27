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

namespace App\Models;

use App\Models\Interfaces\UserInterface;
use App\Models\Interfaces\ImageInterface;

/**
 * Class User.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class User implements UserInterface, \Serializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var array
     */
    protected $currentState;

    /**
     * @var \DateTime
     */
    protected $creationDate;

    /**
     * @var \DateTime
     */
    protected $validationDate;

    /**
     * @var bool
     */
    protected $validated;

    /**
     * @var string
     */
    protected $validationToken;

    /**
     * @var ImageInterface
     */
    protected $profileImage;

    /**
     * {@inheritdoc}
     */
    public function getId(): ? int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): ? string
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
    public function getEmail(): ? string
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
    public function getPlainPassword(): ? string
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
    public function getPassword(): ? string
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
    public function getRoles(): ? array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function setRole(string $role): void
    {
        $this->roles[] = $role;
    }

    /**
     * {@inheritdoc}
     */
    public function getActive(): ? bool
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentState(): ? array
    {
        return $this->currentState;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentState(array $currentState): void
    {
        $this->currentState = $currentState;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationDate(): ? string
    {
        return $this->creationDate->format('D d-m-Y h:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function setCreationDate(\DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationDate(): ? string
    {
        return $this->validationDate->format('D d-m-Y h:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function setValidationDate(\DateTime $validationDate): void
    {
        $this->validationDate = $validationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidated(): ? bool
    {
        return $this->validated;
    }

    /**
     * {@inheritdoc}
     */
    public function setValidated(bool $validated): void
    {
        $this->validated = $validated;
    }

    /**
     * {@inheritdoc}
     */
    public function setValidationToken(string $validationToken): void
    {
        $this->validationToken = $validationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationToken(): ? string
    {
        return $this->validationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileImage(): ? ImageInterface
    {
        return $this->profileImage;
    }

    /**
     * {@inheritdoc}
     */
    public function setProfileImage(ImageInterface $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->active,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->active
            ) = unserialize($serialized);
    }
}
