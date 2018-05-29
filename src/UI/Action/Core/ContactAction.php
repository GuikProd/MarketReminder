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

use App\UI\Action\Core\Interfaces\ContactActionInterface;
use App\UI\Responder\Core\Interfaces\ContactResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactAction
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     name="contact",
 *     path="/contact",
 *     methods={"GET", "POST"}
 * )
 */
class ContactAction implements ContactActionInterface
{
    public function __invoke(
        Request $request,
        ContactResponderInterface $responder
    ) : Response {
        return $responder();
    }
}
