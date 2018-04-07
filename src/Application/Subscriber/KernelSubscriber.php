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

namespace App\Application\Subscriber;

use App\Application\Event\Interfaces\SessionMessageEventInterface;
use App\Application\Event\SessionMessageEvent;
use App\Application\Subscriber\Interfaces\KernelSubscriberInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class KernelSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class KernelSubscriber implements EventSubscriberInterface, KernelSubscriberInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UrlGeneratorInterface $urlGenerator,
        UserRepositoryInterface $userRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onUserAccountValidationRequest'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onUserAccountValidationRequest(GetResponseEvent $event): void
    {
        if ('web_validation' !== $event->getRequest()->attributes->get('_route')) {
            return;
        }

        if (!$event->getRequest()->attributes->get('token')) {
            return;
        }

        if (!$user = $this->userRepository->getUserByToken($event->getRequest()->attributes->get('token'))) {

            $this->eventDispatcher->dispatch(
                SessionMessageEventInterface::NAME,
                new SessionMessageEvent('failure', 'security.validation_failure.notFound_token')
            );

            $event->setResponse(
                new RedirectResponse(
                    $this->urlGenerator->generate('index')
                )
            );
        }

        $event->getRequest()->getSession()->set('user', $user);
    }
}
