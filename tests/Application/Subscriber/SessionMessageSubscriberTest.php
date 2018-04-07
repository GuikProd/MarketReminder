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

use App\Application\Event\SessionMessageEvent;
use App\Application\Subscriber\Interfaces\SessionMessageSubscriberInterface;
use App\Application\Subscriber\SessionMessageSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SessionMessageSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class SessionMessageSubscriberTest extends KernelTestCase
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->translator = static::$kernel->getContainer()->get('translator');
    }

    public function testItImplements()
    {
        $sessionMock = new Session(new MockArraySessionStorage());

        $sessionMessageSubscriber = new SessionMessageSubscriber($sessionMock, $this->translator);

        static::assertInstanceOf(
            EventSubscriberInterface::class,
            $sessionMessageSubscriber
        );

        static::assertInstanceOf(
            SessionMessageSubscriberInterface::class,
            $sessionMessageSubscriber
        );
    }

    public function testSubscribedEvents()
    {
        $sessionMock = new Session(new MockArraySessionStorage());

        $sessionMessageSubscriber = new SessionMessageSubscriber($sessionMock, $this->translator);

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

        $sessionMessageSubscriber = new SessionMessageSubscriber($sessionMock, $this->translator);

        static::assertNull(
            $sessionMessageSubscriber->onSessionMessage($sessionMessageEventMock)
        );
    }

    public function testSessionKeyIsNotDefined()
    {
        $sessionMock = new Session(new MockArraySessionStorage());
        $sessionMessageEventMock = $this->createMock(SessionMessageEvent::class);

        $sessionMessageEventMock->method('getFlashBag')
                                ->willReturn('');

        $sessionMessageSubscriber = new SessionMessageSubscriber($sessionMock, $this->translator);

        static::assertNull(
            $sessionMessageSubscriber->onSessionMessage($sessionMessageEventMock)
        );
    }

    public function testSessionMessageIsInjected()
    {
        $sessionMock = new Session(new MockArraySessionStorage());
        $sessionMessageEventMock = $this->createMock(SessionMessageEvent::class);

        $sessionMessageEventMock->method('getFlashBag')
                                ->willReturn('success');

        $sessionMessageEventMock->method('getMessage')
                                ->willReturn('user.not_found');

        $sessionMessageSubscriber = new SessionMessageSubscriber($sessionMock, $this->translator);
        $sessionMessageSubscriber->onSessionMessage($sessionMessageEventMock);

        static::assertSame(
            ['Les identifiants renseignés ne correspondent pas à un utilisateur existant, veuillez recommencer votre saisie.'],
            $sessionMock->getFlashBag()->get('success')
        );
    }
}
