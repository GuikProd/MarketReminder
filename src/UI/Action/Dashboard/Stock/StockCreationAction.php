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

use App\UI\Action\Dashboard\Stock\Interfaces\StockCreationActionInterface;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockCreationResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StockCreationAction.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     name="dashboard_stock_creation",
 *     path="/dashboard/stock/creation",
 *     methods={"GET", "POST"}
 * )
 */
final class StockCreationAction implements StockCreationActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        StockCreationResponderInterface $responder
    ): Response {
        return $responder($request);
    }
}
