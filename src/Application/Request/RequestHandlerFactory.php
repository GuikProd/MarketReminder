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

namespace App\Application\Request;

use App\Application\Request\Handler\Interfaces\RequestHandlerInterface;
use App\Application\Request\Interfaces\RequestHandlerFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestHandlerFactory.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RequestHandlerFactory implements RequestHandlerFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(Request $request): ?RequestHandlerInterface
    {
        $handler = $request->attributes->get('_request_handler');

        if (!\is_string($handler)) { return null; }

        $class = new $handler();

        return $class::ROUTE_TO_CHECK == $request->attributes->get('_route') && \in_array($request->getMethod(), $class::ALLOWED_METHODS)
            ? $class
            : null;
    }
}
