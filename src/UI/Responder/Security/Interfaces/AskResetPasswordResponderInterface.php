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

namespace App\UI\Responder\Security\Interfaces;

use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Interface AskResetPasswordResponderInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface AskResetPasswordResponderInterface
{
    /**
     * AskResetPasswordResponderInterface constructor.
     *
     * @param Environment            $twig
     * @param PresenterInterface     $presenter
     * @param UrlGeneratorInterface  $urlGenerator
     */
    public function __construct(
        Environment $twig,
        PresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    );

    /**
     * @param bool               $redirect
     * @param FormInterface|null $askResetPasswordTokenFormView
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        $redirect = false,
        FormInterface $askResetPasswordTokenFormView = null
    ): Response;
}
