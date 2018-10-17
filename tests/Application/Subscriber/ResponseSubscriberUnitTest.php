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

use App\Application\Subscriber\Interfaces\ResponseSubscriberInterface;
use App\Application\Subscriber\ResponseSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

/**s
 * Class ResponseSubscriberUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@hotmail.fr>
 */
final class ResponseSubscriberUnitTest extends TestCase
{
    public function testItExist()
    {
        $subscriber = new ResponseSubscriber();

        static::assertInstanceOf(ResponseSubscriberInterface::class, $subscriber);
        static::assertArrayHasKey('kernel.response', $subscriber::getSubscribedEvents());
    }

    public function testItAddHeader()
    {
        $responseMock = Response::create('', 200, []);
        $responseEventMock = $this->createMock(FilterResponseEvent::class);

        $responseEventMock->method('getResponse')->willReturn($responseMock);

        $subscriber = new ResponseSubscriber();

        $subscriber->addCacheHeader($responseEventMock);

        static::assertArrayHasKey(strtolower(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER), $responseMock->headers->all());
    }
}
