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

namespace App\Tests\Domain\DTO\User;

use App\Domain\DTO\User\Interfaces\UserResetPasswordDTOInterface;
use App\Domain\DTO\User\UserResetPasswordDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class UserPasswordResetDTOTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserPasswordResetDTOTest extends TestCase
{
    public function testPasswordResetToken()
    {
        $userPasswordResetDTO = new UserResetPasswordDTO(
                                                 "Toto",
                                                 'toto@gmail.com'
                                             );

        static::assertInstanceOf(
            UserResetPasswordDTOInterface::class,
            $userPasswordResetDTO
        );

        static::assertNotNull(
            $userPasswordResetDTO->getResetToken()
        );
    }
}
