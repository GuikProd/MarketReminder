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

namespace App\Application\Subscriber\Interfaces;

use App\Application\Event\Interfaces\SessionMessageEventInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Interface SessionMessageSubscriberInterface
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface SessionMessageSubscriberInterface
{
    /**
     * SessionMessageSubscriberInterface constructor.
     *
     * @param CloudTranslationRepositoryInterface $repository
     * @param RequestStack $requestStack
     * @param SessionInterface $session
     */
    public function __construct(
        CloudTranslationRepositoryInterface $repository,
        RequestStack $requestStack,
        SessionInterface $session
    );

    /**
     * @param SessionMessageEventInterface $event
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function onSessionMessage(SessionMessageEventInterface $event): void;
}
