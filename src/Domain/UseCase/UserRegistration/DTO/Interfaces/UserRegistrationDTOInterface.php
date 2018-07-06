<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\UseCase\UserRegistration\DTO\Interfaces;

/**
 * Interface UserRegistrationDTOInterface
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserRegistrationDTOInterface
{
    /**
     * UserRegistrationDTOInterface constructor.
     *
     * @param string             $username
     * @param string             $email
     * @param string             $password
     * @param string             $validationToken
     * @param \SplFileInfo|null  $uploadedImage
     */
    public function __construct(
        string $username,
        string $email,
        string $password,
        string $validationToken,
        \SplFileInfo $uploadedImage = null
    );
}
