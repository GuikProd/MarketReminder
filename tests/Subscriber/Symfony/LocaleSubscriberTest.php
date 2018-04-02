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

namespace tests\Subscriber\Symfony;

use PHPUnit\Framework\TestCase;
use App\Subscriber\Symfony\LocaleSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class LocaleSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class LocaleSubscriberTest extends TestCase
{
    public function testLocaleDuringSessionWithoutPreviousSession()
    {
        $requestMock = new Request();
        $requestMock->attributes->set('_locale', 'en');
        $requestMock->cookies->set('session', null);

        $eventMock = $this->createMock(GetResponseEvent::class);
        $eventMock->method('getRequest')
                  ->willReturn($requestMock);

        $localeSubscriber = new LocaleSubscriber('fr');

        static::assertNull(
            $localeSubscriber->onLocaleChange($eventMock)
        );
    }

    public function testLocaleDuringSessionWithPreviousSession()
    {
        $sessionMock = new Session(new MockArraySessionStorage());

        $requestMock = new Request();
        $requestMock->attributes->set('_locale', 'en');
        $requestMock->cookies->set('session', $sessionMock);
        $requestMock->setSession($sessionMock);

        $eventMock = $this->createMock(GetResponseEvent::class);
        $eventMock->method('getRequest')
                  ->willReturn($requestMock);

        $localeSubscriber = new LocaleSubscriber('fr');
        $localeSubscriber->onLocaleChange($eventMock);

        static::assertSame(
            'en',
            $requestMock->attributes->get('_locale')
        );
    }
}
