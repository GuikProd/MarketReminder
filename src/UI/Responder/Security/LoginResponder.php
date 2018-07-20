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
use App\UI\Responder\Security\Interfaces\LoginResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginResponder
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class LoginResponder implements LoginResponderInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        Environment $twig,
        PresenterInterface $presenter
    ) {
        $this->twig = $twig;
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        FormInterface $form
    ): Response {

        $this->presenter->prepareOptions([
            '_locale' => $request->getLocale(),
            'content' => [
                'registration_link' => [
                    'channel' => 'messages',
                    'key' => 'registration.title'
                ]
            ],
            'form' => $form,
            'page' => [
                'title' => [
                    'channel' => 'messages',
                    'key' => 'login.title'
                ]
            ],
            'user' => null
        ]);

        return new Response(
            $this->twig->render('security/login.html.twig', [
                'presenter' => $this->presenter
            ])
        );
    }
}
