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
use App\UI\Responder\Dashboard\Stock\Interfaces\StockCreationResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class StockCreationResponder.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationResponder implements StockCreationResponderInterface
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
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        PresenterInterface $presenter,
        Environment $twig,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->presenter = $presenter;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, FormInterface $form = null, $redirect = false): Response
    {
        $this->presenter->prepareOptions([
            '_locale' => $request->getLocale(),
            'form' => $form,
            'page' => [

            ]
        ]);

        $redirect
            ? $response = new RedirectResponse($this->urlGenerator->generate('dashboard_home'))
            : $response = new Response(
                $this->twig->render('dashboard/stock/stock_creation.html.twig')
            );

        return $response;
    }
}
