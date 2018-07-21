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

namespace App\UI\Action\Dashboard\Interfaces;

use App\UI\Responder\Dashboard\Interfaces\DashboardHomeResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DashboardHomeActionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface DashboardHomeActionInterface
{
    /**
     * @param Request $request
     * @param DashboardHomeResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        DashboardHomeResponderInterface $responder
    ): Response;
}
