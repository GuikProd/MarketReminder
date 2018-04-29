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

use App\UI\Presenter\Security\Interfaces\ResetPasswordPresenterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Interface ResetPasswordResponderInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ResetPasswordResponderInterface
{
    /**
     * ResetPasswordResponderInterface constructor.
     *
     * @param Environment                     $twig
     * @param ResetPasswordPresenterInterface $presenter
     * @param UrlGeneratorInterface           $urlGenerator
     */
    public function __construct(
        Environment $twig,
        ResetPasswordPresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    );

    /**
     * @param bool          $redirect
     * @param FormInterface $form
     *
     * @return Response
     */
    public function __invoke(
        $redirect = false,
        FormInterface $form = null
    ): Response;
}
