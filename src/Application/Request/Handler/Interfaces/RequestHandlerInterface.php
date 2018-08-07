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

namespace App\Application\Request\Handler\Interfaces;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface RequestHandlerInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface RequestHandlerInterface
{
    /**
     * @param Request $request
     */
    public function handle(Request $request): void;

    /**
     * @param Request $request
     */
    public function proceedAMP(Request $request): void;
}
