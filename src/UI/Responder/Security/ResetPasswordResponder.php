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

namespace App\UI\Responder\Security;

use App\UI\Presenter\Security\Interfaces\ResetPasswordPresenterInterface;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class ResetPasswordResponder.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordResponder implements ResetPasswordResponderInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var ResetPasswordPresenterInterface
     */
    private $resetPasswordPresenter;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Environment $twig,
        ResetPasswordPresenterInterface $resetPasswordPresenter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->twig = $twig;
        $this->resetPasswordPresenter = $resetPasswordPresenter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        $redirect = false,
        FormInterface $form = null
    ): Response {

        $this->resetPasswordPresenter->prepareOptions([
            'form' => $form,
            'page' => [
                'title' => 'reset_password.title',
                'button' => [
                    'content' => 'reset_password.button.text'
                ]
            ]
        ]);

        $redirect
            ? $response = new RedirectResponse(
                $this->urlGenerator->generate('index'))
            : $response = new Response(
                $this->twig->render('security/reset_password.html.twig', [
                    'presenter' => $this->resetPasswordPresenter
                ]));

        return $response;
    }
}
