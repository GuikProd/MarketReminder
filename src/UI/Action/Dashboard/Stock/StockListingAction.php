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

namespace App\UI\Action\Dashboard\Stock;

use App\UI\Action\Dashboard\Stock\Interfaces\StockListingActionInterface;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockListingResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StockListingAction.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     name="dashboard_stock_listing",
 *     path="/dashboard/stocks",
 *     methods={"GET"}
 * )
 */
final class StockListingAction implements StockListingActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        StockListingResponderInterface $responder
    ): Response {
        return $responder($request);
    }
}
