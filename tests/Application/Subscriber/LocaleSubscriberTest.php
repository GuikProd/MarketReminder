<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Application\Subscriber;

use App\Application\Subscriber\LocaleSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class LocaleSubscriberTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
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
