<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@hotmail.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Domain\UseCase\UserLogin\DTO;

use App\Domain\UseCase\UserLogin\DTO\Interfaces\UserLoginDTOInterface;
use App\Domain\UseCase\UserLogin\DTO\UserLoginDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class UserLoginDTOUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@hotmail.fr>
 */
final class UserLoginDTOUnitTest extends TestCase
{
    public function testItImplements()
    {
        $dto = new UserLoginDTO('username', 'password');

        static::assertInstanceOf(
            UserLoginDTOInterface::class,
            $dto
        );
    }

    public function testItReceiveData()
    {
        $dto = new UserLoginDTO('username', 'password');

        static::assertSame('username', $dto->username);
        static::assertSame('password', $dto->password);
    }
}
