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

namespace App\Domain\Interactor;

use App\Domain\Interactor\Interfaces\UserInteractorInterface;
use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;

/**
 * Class UserInteractor
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserInteractor implements UserInteractorInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function build(string $email, string $username, string $password, string $validationToken, ImageInterface $profileImage = null): UserInteractorInterface
    {
        $this->user = new User($email, $username, $password, $validationToken, $profileImage);

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
