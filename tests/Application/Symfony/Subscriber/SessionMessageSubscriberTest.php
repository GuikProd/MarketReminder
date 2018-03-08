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

namespace App\Tests\Application\Symfony\Subscriber;

use App\Application\Symfony\Events\SessionMessageEvent;
use App\Application\Symfony\Subscriber\SessionMessageSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class SessionMessageSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class SessionMessageSubscriberTest extends TestCase
{
    public function testSubscribedEvents()
    {
        $sessionMock = new Session(new MockArraySessionStorage());

        $sessionMessageSubscriber = new SessionMessageSubscriber($sessionMock);

        static::assertArrayHasKey(
            SessionMessageEvent::NAME,
            $sessionMessageSubscriber::getSubscribedEvents()
        );
    }

    public function testSessionMessageIsNotDefined()
    {
        $sessionMock = new Session(new MockArraySessionStorage());
        $sessionMessageEventMock = $this->createMock(SessionMessageEvent::class);

        $sessionMessageEventMock->method('getMessage')
                                ->willReturn('');

        $sessionMessageSubscriber = new SessionMessageSubscriber($sessionMock);

        static::assertNull(
            $sessionMessageSubscriber->onSessionMessage($sessionMessageEventMock)
        );
    }
}
