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

namespace tests\Domain\Prototype;

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Prototype\UserPrototype;
use PHPUnit\Framework\TestCase;

/**
 * Class UserPrototypeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserPrototypeTest extends TestCase
{
    public function testItCreateAPrototype()
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getUsername')->willReturn('Toto');
        $user->method('getEmail')->willReturn('toto@gmail.com');

        $userPrototype = new UserPrototype();
        $userPrototype->createFromUser($user);

        static::assertInstanceOf(
            UserInterface::class,
            $userPrototype->getPrototype()
        );
    }
}
