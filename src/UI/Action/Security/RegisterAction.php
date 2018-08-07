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

use App\UI\Action\Security\Interfaces\RegisterActionInterface;
use App\UI\Form\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use App\UI\Form\Type\RegisterType;
use App\UI\Responder\Security\Interfaces\RegisterResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegisterAction.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     name="web_registration",
 *     path="/register",
 *     methods={"GET", "POST"}
 * )
 */
final class RegisterAction implements RegisterActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RegisterTypeHandlerInterface
     */
    private $registerTypeHandler;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RegisterTypeHandlerInterface $registerTypeHandler
    ) {
        $this->formFactory = $formFactory;
        $this->registerTypeHandler = $registerTypeHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        RegisterResponderInterface $responder
    ) {
        $registerType = $this->formFactory->create(RegisterType::class)->handleRequest($request);

        if ($this->registerTypeHandler->handle($registerType)) {
            return $responder($request, true);
        }

        return $responder($request, false, $registerType);
    }
}
