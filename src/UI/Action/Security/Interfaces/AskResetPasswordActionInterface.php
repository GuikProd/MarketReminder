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

namespace App\UI\Action\Security\Interfaces;

use App\UI\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use App\UI\Responder\Security\Interfaces\AskResetPasswordResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface AskResetPasswordActionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface AskResetPasswordActionInterface
{
    /**
     * AskResetPasswordActionInterface constructor.
     *
     * @param FormFactoryInterface                  $formFactory
     * @param AskResetPasswordTypeHandlerInterface  $askResetPasswordTypeHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        AskResetPasswordTypeHandlerInterface $askResetPasswordTypeHandler
    );

    /**
     * @param Request                             $request
     * @param AskResetPasswordResponderInterface  $responder
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        AskResetPasswordResponderInterface $responder
    ): Response;
}
