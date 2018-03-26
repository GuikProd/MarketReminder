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

namespace App\Domain\Interactor\Interfaces;

use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;

/**
 * Interface UserInteractorInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserInteractorInterface
{
    /**
     * @param string $email
     * @param string $username
     * @param string $password
     * @param string $validationToken
     * @param ImageInterface|null $profileImage
     *
     * @return UserInteractorInterface
     */
    public function build(
        string $email,
        string $username,
        string $password,
        string $validationToken,
        ImageInterface $profileImage = null
    ): self;

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface;
}
