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

namespace App\UI\Responder\Security\Interfaces;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Interface ValidationTokenResponderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ValidationTokenResponderInterface
{
    /**
     * ValidationTokenResponderInterface constructor.
     *
     * @param UrlGeneratorInterface  $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator);

    /**
     * @return RedirectResponse
     */
    public function __invoke(): RedirectResponse;
}
