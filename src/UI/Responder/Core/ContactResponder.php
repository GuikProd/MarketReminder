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

namespace App\UI\Responder\Core;

use App\UI\Responder\Core\Interfaces\ContactResponderInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactResponder
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ContactResponder implements ContactResponderInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * ContactResponder constructor.
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
    public function __invoke(): Response
    {
        $response = new Response(
            $this->twig->render('core/contact.html.twig')
        );

        return $response->setCache([
            's_maxage' => 2000,
            'public' => true
        ]);
    }
}
