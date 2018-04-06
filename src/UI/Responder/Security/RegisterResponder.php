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

use App\UI\Responder\Security\Interfaces\RegisterResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class RegisterResponderTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterResponder implements RegisterResponderInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(FormInterface $registerForm): Response
    {
        $response = new Response(
            $this->twig->render('security/register.html.twig', [
                'registerForm' => $registerForm->createView(),
            ])
        );

        return $response->setCache([
            's_maxage' => 600,
            'private' => true,
        ]);
    }
}
