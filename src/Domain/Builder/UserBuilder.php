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

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;

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
    public function createFromRegistration(
        string $email,
        string $username,
        string $password,
        callable $passwordEncoder,
        string $validationToken,
        ImageInterface $profileImage = null
    ): UserBuilderInterface {

        $encryptedPassword = $passwordEncoder($password, null);

        $this->user = new User($email, $username, $encryptedPassword, $validationToken, $profileImage);

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
