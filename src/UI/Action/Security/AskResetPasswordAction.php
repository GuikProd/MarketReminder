<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\UI\Action\Security;

use App\Infra\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use App\UI\Form\Type\AskResetPasswordType;
use App\UI\Responder\Security\AskResetPasswordResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AskResetPasswordAction
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * @Route(
 *     name="web_ask_reset_password",
 *     path="/reset-password/ask"
 * )
 */
class AskResetPasswordAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var AskResetPasswordTypeHandlerInterface
     */
    private $askResetPasswordTypeHandler;

    /**
     * AskResetPasswordAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param AskResetPasswordTypeHandlerInterface $askResetPasswordTypeHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        AskResetPasswordTypeHandlerInterface $askResetPasswordTypeHandler
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->askResetPasswordTypeHandler = $askResetPasswordTypeHandler;
    }

    /**
     * @param Request $request
     * @param AskResetPasswordResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request, AskResetPasswordResponder $responder)
    {
        $askResetPasswordForm = $this->formFactory
                                     ->create(AskResetPasswordType::class)
                                     ->handleRequest($request);

        if ($this->askResetPasswordTypeHandler->handle($askResetPasswordForm)) {
            return $responder(
                null,
                true,
                'index',
                ''
            );
        }

        return $responder(
            $askResetPasswordForm->createView()
        );
    }
}
