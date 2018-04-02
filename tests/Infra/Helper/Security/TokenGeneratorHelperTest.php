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

namespace App\Tests\Infra\Helper\Security;

use App\Infra\Helper\Security\Interfaces\TokenGeneratorHelperInterface;
use App\Infra\Helper\Security\TokenGeneratorHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenGeneratorHelperTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TokenGeneratorHelperTest extends TestCase
{
    public function testResetPasswordTokenGeneration()
    {
        $tokenGeneratorHelper = new TokenGeneratorHelper();

        static::assertInstanceOf(
            TokenGeneratorHelperInterface::class,
            $tokenGeneratorHelper
        );

        static::assertNotNull(
            $tokenGeneratorHelper::generateResetPasswordToken('Toto', 'toto@gmail.com')
        );

        static::assertTrue(
            10 == strlen(
                $tokenGeneratorHelper::generateResetPasswordToken('Toto', 'toto@gmail.com')
            )
        );
    }
}
