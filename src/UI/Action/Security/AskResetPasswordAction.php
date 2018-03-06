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
use App\UI\Responder\Security\AskResetPasswordResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AskResetPasswordAction
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * @Route(
 *     name="web_ask_reset_password",
 *     path="/{_locale}/reset-password/ask",
 *     defaults={
 *         "_locale": "%locale%"
 *     },
 *     requirements={
 *         "_locale": "%accepted_locales%"
 *     }
 * )
 */
class AskResetPasswordAction
{
    /**
     * @var SessionInterface
     */
    private $session;

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
     * @param SessionInterface $session
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param AskResetPasswordTypeHandlerInterface $askResetPasswordTypeHandler
     */
    public function __construct(SessionInterface $session, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, AskResetPasswordTypeHandlerInterface $askResetPasswordTypeHandler)
    {
        $this->session = $session;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->askResetPasswordTypeHandler = $askResetPasswordTypeHandler;
    }

    public function __invoke(AskResetPasswordResponder $responder)
    {
        return $responder();
    }
}
