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

use App\UI\Responder\Security\Interfaces\ValidationTokenResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ValidationTokenResponder.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ValidationTokenResponder implements ValidationTokenResponderInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(): RedirectResponse
    {
        $response = new RedirectResponse(
            $this->urlGenerator->generate('index'),
            RedirectResponse::HTTP_PERMANENTLY_REDIRECT
        );

        return $response;
    }
}
