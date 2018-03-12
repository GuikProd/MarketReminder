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

namespace App\Tests\Domain\UseCase\UserResetPassword\Model;

use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use App\Infra\Helper\Security\TokenGeneratorHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class UserResetPasswordTokenTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserResetPasswordTokenTest extends TestCase
{
    public function testTokenDefinition()
    {
        $token = TokenGeneratorHelper::generateResetPasswordToken('Toto', 'toto@gmail.com');

        $userPasswordReset = new UserResetPasswordToken($token);

        static::assertNotNull(
            $userPasswordReset->getResetPasswordToken()
        );

        static::assertSame(
            $token,
            $userPasswordReset->getResetPasswordToken()
        );
    }
}
