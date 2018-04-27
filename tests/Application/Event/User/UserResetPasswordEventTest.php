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

use App\Application\Event\User\AbstractUserEvent;
use App\Application\Event\User\Interfaces\UserEventInterface;
use App\Application\Event\User\Interfaces\UserResetPasswordEventInterface;
use App\Application\Event\User\UserResetPasswordEvent;
use App\Domain\Models\Interfaces\UserInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UserResetPasswordEventTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserResetPasswordEventTest extends TestCase
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->user = $this->createMock(UserInterface::class);
    }

    public function testEventConstruction()
    {
        $userResetPasswordEvent = new UserResetPasswordEvent($this->user);

        static::assertInstanceOf(AbstractUserEvent::class, $userResetPasswordEvent);
        static::assertInstanceOf(UserEventInterface::class, $userResetPasswordEvent);
        static::assertInstanceOf(UserResetPasswordEventInterface::class, $userResetPasswordEvent);
        static::assertSame('user.password_reset', $userResetPasswordEvent::NAME);
    }

    public function testUserIsInjected()
    {
        $userResetPasswordEvent = new UserResetPasswordEvent($this->user);

        static::assertInstanceOf(UserInterface::class, $userResetPasswordEvent->getUser());
    }
}
