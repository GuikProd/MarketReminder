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

namespace tests\Subscriber\User;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use App\Event\User\UserCreatedEvent;
use App\Models\Interfaces\UserInterface;
use App\Subscriber\User\UserSecuritySubscriber;
use App\Subscriber\Interfaces\UserSecuritySubscriberInterface;

/**
 * Class UserSecuritySubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSecuritySubscriberTest extends TestCase
{
    public function testImplements()
    {
        $twigMock = $this->createMock(Environment::class);
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);

        $userSecuritySubscriber = new UserSecuritySubscriber($twigMock, '', $swiftMailerMock);

        static::assertInstanceOf(
            UserSecuritySubscriberInterface::class,
            $userSecuritySubscriber
        );
    }

    public function testSubscribedEvents()
    {
        $twigMock = $this->createMock(Environment::class);
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);

        $userSecuritySubscriber = new UserSecuritySubscriber($twigMock, '', $swiftMailerMock);

        static::assertArrayHasKey(
            UserCreatedEvent::NAME,
            $userSecuritySubscriber::getSubscribedEvents()
        );
    }

    public function testRegistrationEmailSuccess()
    {
        $twigMock = $this->createMock(Environment::class);
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);

        $userInterfaceMock = $this->createMock(UserInterface::class);
        $userInterfaceMock->method('getEmail')
                          ->willReturn('toto@gmail.com');

        $userCreatedEventMock = $this->createMock(UserCreatedEvent::class);
        $userCreatedEventMock->method('getUser')
                             ->willReturn($userInterfaceMock);

        $userSecuritySubscriber = new UserSecuritySubscriber($twigMock, 'test@marketReminder.com', $swiftMailerMock);

        static::assertNull(
            $userSecuritySubscriber->onUserCreated($userCreatedEventMock)
        );
    }

    public function testRegistrationEmailFailure()
    {
        $twigMock = $this->createMock(Environment::class);
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);

        $userCreatedEventMock = $this->createMock(UserCreatedEvent::class);

        $userSecuritySubscriber = new UserSecuritySubscriber($twigMock, 'test@marketReminder.com', $swiftMailerMock);

        static::assertNull(
            $userSecuritySubscriber->onUserCreated($userCreatedEventMock)
        );
    }
}
