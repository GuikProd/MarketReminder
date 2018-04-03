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

namespace App\Domain\Builder\Interfaces;

use App\Domain\Models\Interfaces\ImageInterface;

/**
 * Interface UserBuilderInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserBuilderInterface
{
    /**
     * @param string               $email
     * @param string               $username
     * @param string               $password
     * @param callable             $passwordEncoder
     * @param string               $validationToken
     * @param ImageInterface|null  $profileImage
     *
     * @return UserBuilderInterface
     */
    public function createFromRegistration(
        string $email,
        string $username,
        string $password,
        callable $passwordEncoder,
        string $validationToken,
        ImageInterface $profileImage = null
    ): UserBuilderInterface;
}
