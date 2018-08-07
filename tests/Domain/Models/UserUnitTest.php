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

namespace App\Tests\Domain\Models;

use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UserUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserUnitTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testItImplementsAndReturnData()
    {
        $user = new User('toto@gmail.com', 'Toto', 'Ie1FDLTOTO');

        static::assertInstanceOf(UserInterface::class, $user);
        static::assertInstanceOf(UuidInterface::class, $user->getId());
        static::assertSame('toto@gmail.com', $user->getEmail());
        static::assertSame('Toto', $user->getUsername());
        static::assertSame('Ie1FDLTOTO', $user->getPassword());
        static::assertNotNull($user->getValidationToken());
    }

    /**
     * @throws \Exception
     */
    public function testImageRelation()
    {
        $image = $this->createMock(ImageInterface::class);
        $image->method('getAlt')->willReturn('toto.png');

        $user = new User('toto@gmail.com', 'Toto', 'Ie1FDLTOTO', $image);

        static::assertInstanceOf(ImageInterface::class, $user->getProfileImage());
    }

    /**
     * @throws \Exception
     */
    public function testUserValidation()
    {
        $user = new User('toto@gmail.com', 'Toto', 'Ie1FDLTOTO');

        $user->validate();

        static::assertTrue($user->getValidated());
        static::assertTrue($user->getActive());
        static::assertNotNull($user->getValidationDate());
        static::assertNull($user->getValidationToken());
    }
}
