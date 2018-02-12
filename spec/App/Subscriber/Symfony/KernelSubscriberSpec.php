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

namespace spec\App\Subscriber\Symfony;

use PhpSpec\ObjectBehavior;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class KernelSubscriberSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class KernelSubscriberSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|SessionInterface       $session
     * @param \PhpSpec\Wrapper\Collaborator|TranslatorInterface    $translator
     * @param \PhpSpec\Wrapper\Collaborator|UrlGeneratorInterface  $urlGenerator
     * @param EntityManagerInterface|\PhpSpec\Wrapper\Collaborator $entityManager
     */
    public function it_implements(
        SessionInterface $session,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ) {
        $this->beConstructedWith($session, $translator, $urlGenerator, $entityManager);
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    /**
     * @param SessionInterface       $session
     * @param TranslatorInterface    $translator
     * @param UrlGeneratorInterface  $urlGenerator
     * @param EntityManagerInterface $entityManager
     */
    public function return_subscribed_events(
        SessionInterface $session,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ) {
        $this->beConstructedWith($session, $translator, $urlGenerator, $entityManager);
        $this::getSubscribedEvents()->shouldContain('onUserValidation');
    }

    /**
     * @param SessionInterface       $session
     * @param TranslatorInterface    $translator
     * @param UrlGeneratorInterface  $urlGenerator
     * @param EntityManagerInterface $entityManager
     * @param GetResponseEvent       $event
     */
    public function should_return_void(
        SessionInterface $session,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager,
        GetResponseEvent $event
    ) {
        $this->beConstructedWith($session, $translator, $urlGenerator, $entityManager);
        $this->onUserValidation($event)->shouldReturn(null);
    }
}
