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

use App\Document\User;
use App\Document\Interfaces\UserInterface;
use App\Builder\Interfaces\UserBuilderInterface;
use App\Models\Interfaces\RegisteredUserInterface;

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
    public function registerUser(RegisteredUserInterface $registeredUser): UserBuilderInterface
    {
        $this->user = new User();

        $this->user->setUsername($registeredUser->getUsername());
        $this->user->setEmail($registeredUser->getEmail());
        $this->user->setPassword($registeredUser->getPassword());

        return $this;
    }
}
