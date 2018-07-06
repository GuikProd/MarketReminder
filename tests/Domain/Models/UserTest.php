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
 * Class UserTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserTest extends TestCase
{
    public function testItImplementsAndReturnData()
    {
        $user = new User(
            'toto@gmail.com',
            'Toto',
            'Ie1FDLTOTO',
            'aa194daz4dz24ad4zd9d9adza4d9d9a'
        );

        static::assertInstanceOf(UserInterface::class, $user);
        static::assertInstanceOf(UuidInterface::class, $user->getId());
        static::assertSame('toto@gmail.com', $user->getEmail());
        static::assertSame('Toto', $user->getUsername());
        static::assertSame('Ie1FDLTOTO', $user->getPassword());
        static::assertSame('aa194daz4dz24ad4zd9d9adza4d9d9a', $user->getValidationToken());
    }

    public function testImageRelation()
    {
        $image = $this->createMock(ImageInterface::class);
        $image->method('getAlt')->willReturn('toto.png');

        $user = new User(
            'toto@gmail.com',
            'Toto',
            'Ie1FDLTOTO',
            'aa194daz4dz24ad4zd9d9adza4d9d9a',
            $image
        );

        static::assertInstanceOf(ImageInterface::class, $user->getProfileImage());
    }

    public function testUserValidation()
    {
        $user = new User(
            'toto@gmail.com',
            'Toto',
            'Ie1FDLTOTO',
            'aa194daz4dz24ad4zd9d9adza4d9d9a'
        );

        $user->validate();

        static::assertTrue($user->getValidated());
        static::assertTrue($user->getActive());
        static::assertNotNull($user->getValidationDate());
        static::assertNull($user->getValidationToken());
    }
}
