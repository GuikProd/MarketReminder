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

namespace App\Tests\Domain\Event\User;

use App\Application\Event\Interfaces\UserEventInterface;
use App\Application\Event\User\UserResetPasswordEvent;
use App\Domain\Models\Interfaces\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserResetPasswordEventTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserResetPasswordEventTest extends TestCase
{
    public function testEventNameIsDefined()
    {
        $userMock = $this->createMock(UserInterface::class);

        $userResetPasswordEvent = new UserResetPasswordEvent($userMock);

        static::assertInstanceOf(
            Event::class,
            $userResetPasswordEvent
        );

        static::assertInstanceOf(
            UserEventInterface::class,
            $userResetPasswordEvent
        );

        static::assertSame(
            'user.password_reset',
            $userResetPasswordEvent::NAME
        );
    }

    public function testUserIsInjected()
    {
        $userMock = $this->createMock(UserInterface::class);

        $userResetPasswordEvent = new UserResetPasswordEvent($userMock);

        static::assertInstanceOf(
            UserInterface::class,
            $userResetPasswordEvent->getUser()
        );
    }
}
