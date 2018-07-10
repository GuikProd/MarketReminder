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

namespace App\UI\Action\Dashboard\Stock\Interfaces;

use App\UI\Form\FormHandler\Dashboard\Interfaces\StockCreationTypeHandlerInterface;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockCreationResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface StockCreationActionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockCreationActionInterface
{
    /**
     * StockCreationActionInterface constructor.
     *
     * @param FormFactoryInterface $factory
     * @param StockCreationTypeHandlerInterface $stockCreationTypeHandler
     */
    public function __construct(
        FormFactoryInterface $factory,
        StockCreationTypeHandlerInterface $stockCreationTypeHandler
    );

    /**
     * @param Request $request
     * @param StockCreationResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        StockCreationResponderInterface $responder
    ): Response;
}
