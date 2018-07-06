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

namespace App\UI\Responder\Security;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class ResetPasswordResponder.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ResetPasswordResponder implements ResetPasswordResponderInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Environment $twig,
        PresenterInterface $resetPasswordPresenter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->twig = $twig;
        $this->presenter = $resetPasswordPresenter;
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
        Request $request,
        $redirect = false,
        FormInterface $form = null
    ): Response {
        
        $this->presenter->prepareOptions([
            '_locale' => $request->attributes->get('_locale'),
            'form' => $form,
            'page' => [
                'title' => [
                    'key' => 'reset_password.title',
                    'channel' => 'messages',
                ],
                'button' => [
                    'key' => 'reset_password.button.text',
                    'channel' => 'messages'
                ]
            ]
        ]);

        $redirect
            ? $response = new RedirectResponse(
                $this->urlGenerator->generate('index'))
            : $response = new Response(
                $this->twig->render('security/reset_password.html.twig', [
                    'presenter' => $this->presenter
                ]));

        return $response;
    }
}
