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

namespace App\Domain\UseCase\UserResetPassword\Model;

/**
 * Class UserResetPasswordToken
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserResetPasswordToken
{
    /**
     * @var string
     */
    private $resetPasswordToken;

    /**
     * UserResetPasswordToken constructor.
     *
     * @param string $resetPasswordToken
     */
    public function __construct(string $resetPasswordToken)
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }
}
