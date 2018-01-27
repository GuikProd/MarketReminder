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

namespace App\Responder\Security;

use Twig\Environment;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegisterResponder
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterResponder
{
    /**
     * @var Environment
     */
    private $twig;

    public function __invoke(FormInterface $registerForm)
    {
        $response = new Response(
            $this->twig->render('security/register.html.twig', [
                'registerForm' => $registerForm->createView()
            ])
        );

        $response->setCache([
            's_maxage' => 600,
            'private' => true
        ]);

        return $response;
    }
}