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

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Interface ResponseSubscriberInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ResponseSubscriberInterface
{
    /**
     * Allow to add a new header which allow to manage both expiration and validation directives.
     *
     * @param FilterResponseEvent $event
     *
     * @return void
     */
    public function addCacheHeader(FilterResponseEvent $event): void;
}
