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

use App\Application\Request\Interfaces\RequestHandlerFactoryInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Interface RequestHandlerSubscriberInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface RequestHandlerSubscriberInterface
{
    /**
     * RequestHandlerSubscriberInterface constructor.
     *
     * @param RequestHandlerFactoryInterface $requestHandlerFactory
     */
    public function __construct(RequestHandlerFactoryInterface $requestHandlerFactory);

    /**
     * @param GetResponseEvent $responseEvent
     */
    public function onRequestHandlerFactoryCheck(GetResponseEvent $responseEvent): void;
}
