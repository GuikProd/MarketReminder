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

namespace App\Application\Request\Handler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class HomeRequestHandler.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class HomeRequestHandler extends AbstractRequestHandler
{
    const ROUTE_TO_CHECK = 'index';
    const ALLOWED_METHODS = ['GET'];

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request): void
    {
        $request->attributes->set('checked', true);
    }
}
