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

use App\UI\Action\Security\Interfaces\AskResetPasswordActionInterface;
use App\UI\Form\FormHandler\Security\Interfaces\AskResetPasswordTypeHandlerInterface;
use App\UI\Form\Type\AskResetPasswordType;
use App\UI\Responder\Security\Interfaces\AskResetPasswordResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AskResetPasswordAction.
 *
 * @package App\UI\Action\Security
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route({
 *         "fr": "/reinitialisation/mdp",
 *         "en": "/reset-password/ask"
 *     },
 *     name="web_ask_reset_password"
 * )
 */
final class AskResetPasswordAction implements AskResetPasswordActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var AskResetPasswordTypeHandlerInterface
     */
    private $askResetPasswordTypeHandler;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        AskResetPasswordTypeHandlerInterface $askResetPasswordTypeHandler
    ) {
        $this->formFactory = $formFactory;
        $this->askResetPasswordTypeHandler = $askResetPasswordTypeHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        AskResetPasswordResponderInterface $responder
    ): Response {

        $askResetPasswordForm = $this->formFactory->create(AskResetPasswordType::class)
                                                  ->handleRequest($request);

        if ($this->askResetPasswordTypeHandler->handle($askResetPasswordForm)) {
            return $responder(true, $request);
        }

        return $responder(false, $request, $askResetPasswordForm);
    }
}
