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

namespace App\UI\Responder\Core\Interfaces;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ContactResponderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ContactResponderInterface
{
    /**
     * @return Response
     */
    public function __invoke(): Response;
}
