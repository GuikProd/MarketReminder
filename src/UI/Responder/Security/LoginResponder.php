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

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginResponder
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class LoginResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * LoginResponder constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param \Exception $exception
     * @param string $username
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        \Exception $exception = null,
        string $username = null
    ) {
        $response = new Response(
            $this->twig->render('security/login.html.twig', [
                'username' => $username,
                'errors' => $exception
            ])
        );

        return $response->setCache([
            'public' => true
        ]);
    }
}
