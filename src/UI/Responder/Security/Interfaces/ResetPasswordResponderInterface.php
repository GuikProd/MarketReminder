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
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request       $request
     * @param bool          $redirect
     * @param FormInterface $form
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        $redirect = false,
        FormInterface $form = null
    ): Response;
}
