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

namespace tests\Builder;

use App\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;
use App\Models\Interfaces\UserInterface;
use App\Models\Interfaces\ImageInterface;

/**
 * Class UserBuilderTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserBuilderTest extends TestCase
{
    public function testRegistration()
    {
        $userBuilder = new UserBuilder();
        $imageMock = $this->createMock(ImageInterface::class);
        $imageMock->method('getId')
                  ->willReturn(0);

        $userBuilder->createUser()
            ->withUsername('Toto')
            ->withEmail('toto@gmail.com')
            ->withPlainPassword('Ie1FDLTOTO')
            ->withPassword('Ie1FDLTOTO')
            ->withCreationDate(new \DateTime('03-02-2018'))
            ->withRole('ROLE_USER')
            ->withValidated(false)
            ->withValidationDate(new \DateTime('03-02-2018'))
            ->withActive(false)
            ->withValidationToken('Ie1FDLTOTO')
            ->withCurrentState(['toValidate'])
            ->withProfileImage($imageMock);

        static::assertNull($userBuilder->getUser()->getId());
        static::assertFalse($userBuilder->getUser()->getActive());
        static::assertFalse($userBuilder->getUser()->getValidated());
        static::assertSame('Toto', $userBuilder->getUser()->getUsername());
        static::assertContains('ROLE_USER', $userBuilder->getUser()->getRoles());
        static::assertInstanceOf(UserInterface::class, $userBuilder->getUser());
        static::assertSame('Ie1FDLTOTO', $userBuilder->getUser()->getPassword());
        static::assertSame('toto@gmail.com', $userBuilder->getUser()->getEmail());
        static::assertSame(0, $userBuilder->getUser()->getProfileImage()->getId());
        static::assertEquals(0, $userBuilder->getUser()->getProfileImage()->getId());
        static::assertSame('Ie1FDLTOTO', $userBuilder->getUser()->getPlainPassword());
        static::assertContains('toValidate', $userBuilder->getUser()->getCurrentState());
        static::assertSame('Ie1FDLTOTO', $userBuilder->getUser()->getValidationToken());
        static::assertEquals('Sat 03-02-2018 12:00:00', $userBuilder->getUser()->getCreationDate());
        static::assertEquals('Sat 03-02-2018 12:00:00', $userBuilder->getUser()->getValidationDate());
    }
}
