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
use App\UI\Form\FormHandler\Dashboard\Interfaces\StockCreationTypeHandlerInterface;
use App\UI\Form\Type\Stock\StockCreationType;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockCreationResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
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
 *
 * @Security("has_role('ROLE_USER')")
 */
final class StockCreationAction implements StockCreationActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var StockCreationTypeHandlerInterface
     */
    private $stockCreationHandler;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        StockCreationTypeHandlerInterface $stockCreationHandler
    ) {
        $this->formFactory = $formFactory;
        $this->stockCreationHandler = $stockCreationHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        StockCreationResponderInterface $responder
    ): Response {

        $form = $this->formFactory->create(StockCreationType::class)->handleRequest($request);

        if ($this->stockCreationHandler->handle($form)) {
            return $responder($request, null, true);
        }

        return $responder($request, $form);
    }
}
