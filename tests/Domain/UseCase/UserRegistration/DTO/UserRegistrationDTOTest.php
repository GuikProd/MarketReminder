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

namespace App\Tests\Domain\UseCase\UserRegistration\DTO;

use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\UseCase\UserRegistration\DTO\Interfaces\UserRegistrationDTOInterface;
use App\Domain\UseCase\UserRegistration\DTO\UserRegistrationDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class UserRegistrationDTOTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserRegistrationDTOTest extends TestCase
{
    public function testClassAttributes()
    {
        static::assertClassHasAttribute('username', UserRegistrationDTO::class);
        static::assertClassHasAttribute('email', UserRegistrationDTO::class);
        static::assertClassHasAttribute('password', UserRegistrationDTO::class);
        static::assertClassHasAttribute('validationToken', UserRegistrationDTO::class);
    }

    public function testItImplements()
    {
        $userRegistrationDTO = new UserRegistrationDTO(
                                   'Toto',
                                   'toto@gmail.com',
                                   'Ie1FDLTOTO',
                                   ''
                               );

        static::assertInstanceOf(
            UserRegistrationDTOInterface::class,
            $userRegistrationDTO
        );
    }

    public function testProfileImageIsDefined()
    {
        $imageMock = $this->createMock(ImageInterface::class);

        $userRegistrationDTO = new UserRegistrationDTO(
            'Toto',
            'toto@gmail.com',
            'Ie1FDLTOTO',
            '',
            $imageMock
        );

        static::assertInstanceOf(
            ImageInterface::class,
            $userRegistrationDTO->profileImage
        );
    }
}
