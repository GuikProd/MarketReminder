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

use App\UI\Presenter\Security\Interfaces\AskResetPasswordPresenterInterface;
use App\UI\Responder\Security\Interfaces\AskResetPasswordResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class AskResetPasswordResponder
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordResponder implements AskResetPasswordResponderInterface
{
    /**
     * @var AskResetPasswordPresenterInterface
     */
    private $askResetPresenter;

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
        AskResetPasswordPresenterInterface $presenter,
        Environment $twig,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->askResetPresenter = $presenter;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        $isRedirect = false,
        FormInterface $askResetPasswordForm = null
    ): Response {

        $this->askResetPresenter->prepareOptions([
            'card' => [
                'header' => 'security.resetPasswordToken_header',
                'button' => 'security.resetPasswordToken',
            ],
            'form' => $askResetPasswordForm,
            'page' => [
                'title' => 'resetPassword.title'
            ]
        ]);

        $isRedirect
            ? $response = new RedirectResponse($this->urlGenerator->generate('index'))
            : $response = new Response(
                $this->twig->render('security/ask_reset_password_token.html.twig', [
                    'presenter' => $this->askResetPresenter
                ])
            );

        return $response;
    }
}
