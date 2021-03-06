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

namespace App\Domain\UseCase\UserRegistration\DTO;

use App\Domain\UseCase\UserRegistration\DTO\Interfaces\UserRegistrationDTOInterface;

/**
 * Class UserRegistrationDTO
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserRegistrationDTO implements UserRegistrationDTOInterface
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var \SplFileInfo
     */
    public $profileImage;

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
        string $password,
        string $validationToken,
        \SplFileInfo $uploadedImage = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->validationToken = $validationToken;
        $this->profileImage = $uploadedImage;
    }
}
