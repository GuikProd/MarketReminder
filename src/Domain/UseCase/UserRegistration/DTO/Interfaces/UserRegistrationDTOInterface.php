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

namespace App\Domain\UseCase\UserRegistration\DTO\Interfaces;
use App\Domain\Models\Interfaces\ImageInterface;

/**
 * Interface UserRegistrationDTOInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserRegistrationDTOInterface
{
    /**
     * UserRegistrationDTOInterface constructor.
     *
     * @param string              $username
     * @param string              $email
     * @param string              $password
     * @param string              $validationToken
     * @param ImageInterface|null $profileImage
     */
    public function __construct(
        string $username,
        string $email,
        string $password,
        string $validationToken,
        ImageInterface $profileImage = null
    );
}
