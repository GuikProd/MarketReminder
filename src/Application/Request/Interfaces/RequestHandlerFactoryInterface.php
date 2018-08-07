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

namespace App\Application\Request\Interfaces;

use App\Application\Request\Handler\Interfaces\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface RequestHandlerFactoryInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface RequestHandlerFactoryInterface
{
    /**
     * @param Request $request
     *
     * @return RequestHandlerInterface|null
     */
    public function create(Request $request): ?RequestHandlerInterface;
}
