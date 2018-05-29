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

namespace App\Tests\Domain\UseCase\UserResetPassword\DTO;

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserResetPasswordDTOInterface;
use App\Domain\UseCase\UserResetPassword\DTO\UserResetPasswordDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class UserResetPasswordDTOTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserResetPasswordDTOTest extends TestCase
{
    public function testPasswordResetToken()
    {
        $userPasswordResetDTO = new UserResetPasswordDTO(
                                                 'toto@gmail.com',
                                                 'Toto'
                                             );

        static::assertInstanceOf(UserResetPasswordDTOInterface::class, $userPasswordResetDTO);
        static::assertSame('Toto', $userPasswordResetDTO->username);
        static::assertSame('toto@gmail.com', $userPasswordResetDTO->email);
    }
}
