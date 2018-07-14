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

namespace App\UI\Responder\Security\Interfaces;

use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Interface LoginResponderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface LoginResponderInterface
{
    /**
     * LoginResponderInterface constructor.
     *
     * @param Environment $twig
     * @param PresenterInterface $presenter
     */
    public function __construct(
        Environment $twig,
        PresenterInterface $presenter
    );

    /**
     * @param Request $request
     * @param FormInterface $form
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        FormInterface $form
    ): Response;
}
