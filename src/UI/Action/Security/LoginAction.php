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

namespace App\UI\Action\Security;

use App\UI\Action\Security\Interfaces\LoginActionInterface;
use App\UI\Form\Type\LoginType;
use App\UI\Responder\Security\Interfaces\LoginResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LoginAction
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     name="web_login",
 *     path="/login",
 *     methods={"GET", "POST"}
 * )
 */
final class LoginAction implements LoginActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        LoginResponderInterface $responder
    ): Response {

        $form = $this->formFactory->create(LoginType::class)->handleRequest($request);

        return $responder($request, $form);
    }
}
