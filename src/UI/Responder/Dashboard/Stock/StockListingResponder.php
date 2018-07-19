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

namespace App\UI\Responder\Dashboard\Stock;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockListingResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class StockListingResponder.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockListingResponder implements StockListingResponderInterface
{
    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Environment $twig,
        PresenterInterface $presenter
    ) {
        $this->twig = $twig;
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, array $data = []): Response
    {
        $this->presenter->prepareOptions([
            '_locale' => $request->getLocale(),
            'content' => [
                'data' => $data
            ],
            'page' => []
        ]);

        return new Response(
            $this->twig->render('dashboard/stock/stock_listing.html.twig', [
                'presenter' => $this->presenter
            ])
        );
    }
}
