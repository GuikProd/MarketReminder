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

namespace App\UI\Responder\Dashboard\Stock\Interfaces;

use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Interface StockListingResponderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockListingResponderInterface
{
    /**
     * StockListingResponderInterface constructor.
     *
     * @param Environment $twig
     * @param PresenterInterface $presenter
     */
    public function __construct(Environment $twig, PresenterInterface $presenter);

    /**
     * @param Request $request
     * @param array $data
     *
     * @return Response
     */
    public function __invoke(Request $request, array $data = []): Response;
}
