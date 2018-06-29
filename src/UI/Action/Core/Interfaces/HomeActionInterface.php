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
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Interface HomeActionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface HomeActionInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param HomeResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(
        ServerRequestInterface $request,
        HomeResponderInterface $responder
    ): Response;
}
