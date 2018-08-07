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

use App\Application\Request\Interfaces\RequestHandlerFactoryInterface;
use App\Application\Subscriber\Interfaces\RequestHandlerSubscriberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RequestHandlerSubscriber.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RequestHandlerSubscriber implements RequestHandlerSubscriberInterface, EventSubscriberInterface
{
    /**
     * @var RequestHandlerFactoryInterface
     */
    private $requestHandlerFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(RequestHandlerFactoryInterface $requestHandlerFactory)
    {
        $this->requestHandlerFactory = $requestHandlerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onRequestHandlerFactoryCheck'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onRequestHandlerFactoryCheck(GetResponseEvent $responseEvent): void
    {
        $handler = $this->requestHandlerFactory->create($responseEvent->getRequest());

        if (!\is_object($handler)) { return; }

        $handler->handle($responseEvent->getRequest());
    }
}
