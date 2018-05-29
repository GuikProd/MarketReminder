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
use App\UI\Responder\Security\Interfaces\AskResetPasswordResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class AskResetPasswordResponder
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class AskResetPasswordResponder implements AskResetPasswordResponderInterface
{
    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Environment $twig,
        PresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->presenter = $presenter;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        $isRedirect = false,
        Request $request,
        FormInterface $askResetPasswordForm = null
    ): Response {

        $this->presenter->prepareOptions([
            '_locale' => $request->getLocale(),
            'form' => $askResetPasswordForm,
            'page' => [
                'card_button' => [
                    'key' => 'security.resetPasswordToken',
                    'channel' => 'messages'
                ],
                'card_header' => [
                    'key' => 'security.resetPasswordToken_header',
                    'channel' => 'messages'
                ],
                'title' => [
                    'key' => 'reset_password.title',
                    'channel' => 'messages'
                ]
            ]
        ]);

        $isRedirect
            ? $response = new RedirectResponse($this->urlGenerator->generate('index'))
            : $response = new Response(
                $this->twig->render('security/ask_reset_password_token.html.twig', [
                    'presenter' => $this->presenter
                ])
            );

        return $response;
    }
}
