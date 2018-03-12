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

namespace App\Domain\UseCase\UserRegistration\DTO;

use App\Domain\UseCase\UserRegistration\DTO\Interfaces\UserRegistrationDTOInterface;

/**
 * Class UserRegistrationDTO
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserRegistrationDTO implements UserRegistrationDTOInterface
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $plainPassword;

    /**
     * @var string
     */
    public $validationToken;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $username,
        string $email,
        string $plainPassword,
        string $validationToken
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->validationToken = $validationToken;
    }
}
