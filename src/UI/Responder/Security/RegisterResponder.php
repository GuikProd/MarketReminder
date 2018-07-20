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
use App\UI\Responder\Security\Interfaces\RegisterResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class RegisterResponder.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RegisterResponder implements RegisterResponderInterface
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
        $this->twig = $twig;
        $this->presenter = $presenter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        bool $redirect = false,
        FormInterface $registerForm = null
    ): Response {

        $this->presenter->prepareOptions([
            '_locale' => $request->getLocale(),
            'content' => [
                'file_size_error' => [
                    'key' => 'register.profileImage_size_error',
                    'channel' => 'form',
                ],
                'file_size_success' => [
                    'key' => 'register.profileImage_size_success',
                    'channel' => 'form',
                ],
                'maxFileSize' => 2000000
            ],
            'form' => $registerForm,
            'page' => [
                'title' => [
                    'key' => 'registration.title',
                    'channel' => 'messages'
                ]
            ]
        ]);

        $redirect
            ? $response = new RedirectResponse($this->urlGenerator->generate('index'))
            : $response = new Response($this->twig->render('security/register.html.twig', [
                'presenter' => $this->presenter,
            ])
        );

        return $response->setCache([
            'etag' => md5(str_rot13($response->getContent()))
        ]);
    }
}
