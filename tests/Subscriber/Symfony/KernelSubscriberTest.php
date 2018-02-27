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

use Doctrine\ORM\EntityManagerInterface;
use App\Subscriber\Symfony\KernelSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class KernelSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class KernelSubscriberTest extends KernelTestCase
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->session = new Session(new MockArraySessionStorage());
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->urlGenerator->method('generate')->willReturn('/fr/');
        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testSubscribedEvents()
    {
        $requestMock = new Request();
        $requestMock->attributes->set('_route', 'web_validation');
        $requestMock->attributes->set('token', 'EdFEDNRanuLs5');

        $kernelRequestEvent = $this->createMock(GetResponseEvent::class);
        $kernelRequestEvent->method('getRequest')
                           ->willReturn($requestMock);

        $kernelSubscriber = new KernelSubscriber(
                                $this->session,
                                $this->translator,
                                $this->urlGenerator,
                                $this->entityManager
                            );

        static::assertArrayHasKey(
            KernelEvents::REQUEST,
            $kernelSubscriber::getSubscribedEvents()
        );
    }

    public function testUserValidation()
    {
        $requestMock = new Request();
        $requestMock->attributes->set('_route', 'web_validation');
        $requestMock->attributes->set('token', 'EdFEDNRanuLs5');

        $kernelRequestEvent = $this->createMock(GetResponseEvent::class);
        $kernelRequestEvent->method('getRequest')
                           ->willReturn($requestMock);

        $kernelSubscriber = new KernelSubscriber(
                                $this->session,
                                $this->translator,
                                $this->urlGenerator,
                                $this->entityManager
                            );

        static::assertNull(
            $kernelSubscriber->onUserValidation($kernelRequestEvent)
        );
    }

    public function testWrongPath()
    {
        $requestMock = new Request();
        $requestMock->attributes->set('_route', 'web_register');
        $requestMock->attributes->set('token', 'EdFEDNRanuLs5');

        $kernelRequestEvent = $this->createMock(GetResponseEvent::class);
        $kernelRequestEvent->method('getRequest')
            ->willReturn($requestMock);

        $kernelSubscriber = new KernelSubscriber(
                                $this->session,
                                $this->translator,
                                $this->urlGenerator,
                                $this->entityManager
                            );

        static::assertNull(
            $kernelSubscriber->onUserValidation($kernelRequestEvent)
        );
    }
}
