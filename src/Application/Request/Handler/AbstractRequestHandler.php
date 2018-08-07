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

namespace App\Application\Request\Handler;

use App\Application\Request\Handler\Interfaces\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractRequestHandler.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class AbstractRequestHandler implements RequestHandlerInterface
{
    /**
     * @param Request $request
     */
    public function proceedAMP(Request $request): void
    {

    }
}
