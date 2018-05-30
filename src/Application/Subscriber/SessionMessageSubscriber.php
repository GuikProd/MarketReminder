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

namespace App\Application\Subscriber;

use App\Application\Event\Interfaces\SessionMessageEventInterface;
use App\Application\Subscriber\Interfaces\SessionMessageSubscriberInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SessionMessageSubscriber
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class SessionMessageSubscriber implements EventSubscriberInterface, SessionMessageSubscriberInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        CloudTranslationRepositoryInterface $repository,
        RequestStack $requestStack,
        SessionInterface $session
    ) {
        $this->cloudTranslationRepository = $repository;
        $this->requestStack = $requestStack;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SessionMessageEventInterface::NAME => 'onSessionMessage'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onSessionMessage(SessionMessageEventInterface $event): void
    {
        if ('' === $event->getMessage() || !$event->getFlashBag()) {
            return;
        }

        $cacheEntry = $this->cloudTranslationRepository->getSingleEntry(
            'session'.'.'.$this->requestStack->getCurrentRequest()->getLocale().'.yaml',
            $this->requestStack->getCurrentRequest()->getLocale(),
            $event->getMessage()
        );

        $this->session->getFlashBag()->add($event->getFlashBag(), $cacheEntry->getValue());
    }
}
