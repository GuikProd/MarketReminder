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

namespace App\Tests\Application\Symfony\Subscriber;

use App\Application\Event\Interfaces\SessionMessageEventInterface;
use App\Application\Event\SessionMessageEvent;
use App\Application\Subscriber\Interfaces\SessionMessageSubscriberInterface;
use App\Application\Subscriber\SessionMessageSubscriber;
use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class SessionMessageSubscriberUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class SessionMessageSubscriberUnitTest extends TestCase
{
    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var SessionMessageSubscriberInterface
     */
    private $sessionMessageSubscriber;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getLocale')->willReturn('fr');

        $this->cloudTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->session = new Session(new MockArraySessionStorage());
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->sessionMessageSubscriber = new SessionMessageSubscriber($this->cloudTranslationRepository, $this->requestStack, $this->session);

        $this->requestStack->method('getCurrentRequest')->willReturn($requestMock);
    }

    public function testItImplements()
    {
        static::assertInstanceOf(
            EventSubscriberInterface::class,
            $this->sessionMessageSubscriber
        );

        static::assertInstanceOf(
            SessionMessageSubscriberInterface::class,
            $this->sessionMessageSubscriber
        );
    }

    public function testSubscribedEvents()
    {
        static::assertArrayHasKey(
            SessionMessageEvent::NAME,
            $this->sessionMessageSubscriber::getSubscribedEvents()
        );
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testSessionMessageIsNotDefined()
    {
        static::expectException(\InvalidArgumentException::class);

        $sessionMessageEventMock = $this->createMock(SessionMessageEventInterface::class);

        $sessionMessageEventMock->method('getMessage')->willReturn('');

        $this->sessionMessageSubscriber->onSessionMessage($sessionMessageEventMock);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testSessionKeyIsNotDefined()
    {
        static::expectException(\InvalidArgumentException::class);

        $sessionMessageEventMock = $this->createMock(SessionMessageEventInterface::class);

        $sessionMessageEventMock->method('getFlashBag')->willReturn('');

        $this->sessionMessageSubscriber->onSessionMessage($sessionMessageEventMock);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $key
     * @param string $entry
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testSessionMessageIsInjected(string $key, string $entry)
    {
        $sessionMessageEventMock = $this->createMock(SessionMessageEventInterface::class);
        $sessionMessageEventMock->method('getFlashBag')->willReturn($key);
        $sessionMessageEventMock->method('getMessage')->willReturn($entry);

        $this->cloudTranslationRepository->method('getSingleEntry')->willReturn(new CloudTranslationItem([
            '_locale' => $this->requestStack->getCurrentRequest()->getLocale(),
            'channel' => 'session',
            'tag' => Uuid::uuid4()->toString(),
            'key' => $key,
            'value' => $entry
        ]));

        $this->sessionMessageSubscriber->onSessionMessage($sessionMessageEventMock);

        static::assertSame([$entry], $this->session->getFlashBag()->get($key));
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('success', 'user.found');
        yield array('failure', 'user.not_found');
        yield array('info', 'user.credentials_information');
    }
}
