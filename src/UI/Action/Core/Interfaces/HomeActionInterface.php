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

namespace App\UI\Action\Core\Interfaces;

use App\UI\Responder\Core\Interfaces\HomeResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface HomeActionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface HomeActionInterface
{
    /**
     * @param Request $request
     * @param HomeResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        HomeResponderInterface $responder
    ): Response;
}
