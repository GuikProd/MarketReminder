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

namespace App\Tests\Domain\UseCase\UserResetPassword\Model;

use App\Application\Helper\Security\TokenGeneratorHelper;
use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use PHPUnit\Framework\TestCase;

/**
 * Class UserResetPasswordTokenTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
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
