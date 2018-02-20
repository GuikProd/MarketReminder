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

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ValidationTokenResponder.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ValidationTokenResponder
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * ValidationTokenResponder constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return RedirectResponse
     */
    public function __invoke()
    {
        $response = new RedirectResponse(
            $this->urlGenerator->generate('index'),
            RedirectResponse::HTTP_PERMANENTLY_REDIRECT
        );

        return $response->setCache([
            's_maxage' => 32800,
            'public' => true
        ]);
    }
}
