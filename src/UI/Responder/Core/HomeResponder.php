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

namespace App\UI\Responder\Core;

use App\UI\Presenter\Core\Interfaces\HomePresenterInterface;
use App\UI\Responder\Core\Interfaces\HomeResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class HomeResponder.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class HomeResponder implements HomeResponderInterface
{
    /**
     * @var HomePresenterInterface
     */
    private $homePresenter;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Environment $twig,
        HomePresenterInterface $presenter
    ) {
        $this->twig = $twig;
        $this->homePresenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request): Response
    {
        $this->homePresenter->prepareOptions([
            'page' => [
                'title' => 'home.title'
            ]
        ]);

        return new Response(
            $this->twig->render('core/index.html.twig', [
                'presenter' => $this->homePresenter
            ])
        );
    }
}
