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

namespace tests\Domain\Subscriber;

use App\Domain\Event\User\UserCreatedEvent;
use App\Domain\Event\User\UserResetPasswordEvent;
use App\Domain\Event\User\UserValidatedEvent;
use App\Domain\Subscriber\Interfaces\UserSubscriberInterface;
use App\Domain\Subscriber\UserSubscriber;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * Class UserSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSubscriberTest extends TestCase
{
    public function testItImplements()
    {
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);
        $emailSenderMock = 'test@marketReminder.com';
        $twigMock = $this->createMock(Environment::class);

        $userSubscriber = new UserSubscriber($emailSenderMock, $swiftMailerMock, $twigMock);

        static::assertClassHasAttribute(
            'swiftMailer',
            UserSubscriber::class
        );

        static::assertInstanceOf(
            UserSubscriberInterface::class,
            $userSubscriber
        );

        static::assertInstanceOf(
            UserSubscriberInterface::class,
            $userSubscriber
        );
    }

    public function testUserCreatedEventIsListened()
    {
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);
        $emailSenderMock = 'test@marketReminder.com';
        $twigMock = $this->createMock(Environment::class);

        $userSubscriber = new UserSubscriber($emailSenderMock, $swiftMailerMock, $twigMock);

        static::assertArrayHasKey(
            UserCreatedEvent::NAME,
            $userSubscriber::getSubscribedEvents()
        );
    }

    public function testUserValidatedEventIsListened()
    {
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);
        $emailSenderMock = 'test@marketReminder.com';
        $twigMock = $this->createMock(Environment::class);

        $userSubscriber = new UserSubscriber($emailSenderMock, $swiftMailerMock, $twigMock);

        static::assertArrayHasKey(
            UserValidatedEvent::NAME,
            $userSubscriber::getSubscribedEvents()
        );
    }

    public function testUserResetPasswordEventIsListened()
    {
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);
        $emailSenderMock = 'test@marketReminder.com';
        $twigMock = $this->createMock(Environment::class);

        $userSubscriber = new UserSubscriber($emailSenderMock, $swiftMailerMock, $twigMock);

        static::assertArrayHasKey(
            UserResetPasswordEvent::NAME,
            $userSubscriber::getSubscribedEvents()
        );
    }

    public function testUserResetPasswordEventLogicIsTriggered()
    {
        $userResetPasswordEventMock = $this->createMock(UserResetPasswordEvent::class);
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);
        $emailSenderMock = 'test@marketReminder.com';
        $twigMock = $this->createMock(Environment::class);

        $userSubscriber = new UserSubscriber($emailSenderMock, $swiftMailerMock, $twigMock);

        static::assertNull(
            $userSubscriber->onUserResetPassword($userResetPasswordEventMock)
        );
    }
}
