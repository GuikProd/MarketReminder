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
use App\Document\Interfaces\UserInterface;
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
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
