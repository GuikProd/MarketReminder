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

namespace App\UI\Responder\Dashboard;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class DashboardHomeResponder
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class DashboardHomeResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * DashboardHomeResponder constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke()
    {
        $response = new Response(
            $this->twig->render('dashboard/dashboard_home.html.twig')
        );

        return $response->setCache([
            's_maxage' => 2000,
            'public' => true
        ]);
    }
}
