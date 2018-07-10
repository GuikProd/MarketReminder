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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Interface StockCreationResponderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockCreationResponderInterface
{
    /**
     * StockCreationResponderInterface constructor.
     *
     * @param PresenterInterface $presenter
     * @param Environment $twig
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        PresenterInterface $presenter,
        Environment $twig,
        UrlGeneratorInterface $urlGenerator
    );

    /**
     * @param Request $request
     *
     * @param FormInterface|null $form
     * @param bool $redirect
     * @return Response
     */
    public function __invoke(
        Request $request,
        FormInterface $form = null,
        $redirect = false
    ): Response;
}
