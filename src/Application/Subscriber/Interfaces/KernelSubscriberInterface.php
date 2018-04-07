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

namespace App\Application\Subscriber\Interfaces;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Interface KernelSubscriberInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface KernelSubscriberInterface
{
    /**
     * KernelSubscriberInterface constructor.
     *
     * @param EventDispatcherInterface  $eventDispatcher
     * @param UrlGeneratorInterface     $urlGenerator
     * @param UserRepositoryInterface   $userRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UrlGeneratorInterface $urlGenerator,
        UserRepositoryInterface $userRepository
    );

    /**
     * @param GetResponseEvent $event
     */
    public function onUserAccountValidationRequest(GetResponseEvent $event): void;
}
