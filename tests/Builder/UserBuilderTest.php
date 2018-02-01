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
        $userBuilder->createUser()
                    ->withUsername('Toto')
                    ->withEmail('toto@gmail.com')
                    ->withPlainPassword('Ie1FDLTOTO');

        static::assertInstanceOf(
            UserInterface::class,
            $userBuilder->getUser()
        );

        static::assertSame(
            'Toto',
            $userBuilder->getUser()->getUsername()
        );

        static::assertSame(
            'toto@gmail.com',
            $userBuilder->getUser()->getEmail()
        );
    }
}
