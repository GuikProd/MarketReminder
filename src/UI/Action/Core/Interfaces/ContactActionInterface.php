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

namespace App\UI\Action\Core\Interfaces;

use App\UI\Responder\Core\Interfaces\ContactResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ContactActionInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ContactActionInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request, ContactResponderInterface $responder): Response;
}
