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

namespace App\UI\Action\Core;

use App\UI\Action\Core\Interfaces\HomeActionInterface;
use App\UI\Responder\Core\Interfaces\HomeResponderInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Diactoros\Response;

/**
 * Class HomeAction.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     name="index",
 *     path="/",
 *     methods={"GET"},
 *     requirements={
 *         "_locale": "%accepted_locales%"
 *     }
 * )
 */
final class HomeAction implements HomeActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(
        ServerRequestInterface $request,
        HomeResponderInterface $responder
    ): Response {
        return $responder($request);
    }
}
