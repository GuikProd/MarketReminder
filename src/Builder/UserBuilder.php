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

namespace App\Builder;

use App\Interactor\UserInteractor;
use App\Models\Interfaces\UserInterface;
use App\Builder\Interfaces\UserBuilderInterface;

/**
 * Class UserBuilder
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserBuilder implements UserBuilderInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function createUser(): UserBuilderInterface
    {
        $this->user = new UserInteractor();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withUsername(string $username): UserBuilderInterface
    {
        $this->user->setUsername($username);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withEmail(string $email): UserBuilderInterface
    {
        $this->user->setEmail($email);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withPlainPassword(string $plainPassword): UserBuilderInterface
    {
        $this->user->setPlainPassword($plainPassword);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withPassword(string $password): UserBuilderInterface
    {
        $this->user->setPassword($password);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withCreationDate(\DateTime $creationDate): UserBuilderInterface
    {
        $this->user->setCreationDate($creationDate);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withValidationDate(\DateTime $validationDate): UserBuilderInterface
    {
        $this->user->setValidationDate($validationDate);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withRole(string $role): UserBuilderInterface
    {
        $this->user->setRole($role);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withValidated(bool $validated): UserBuilderInterface
    {
        $this->user->setValidated($validated);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withValidationToken(string $validationToken): UserBuilderInterface
    {
        $this->user->setValidationToken($validationToken);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withActive(bool $active): UserBuilderInterface
    {
        $this->user->setActive($active);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withCurrentState(string $currentState): UserBuilderInterface
    {
        $this->user->setCurrentState($currentState);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
