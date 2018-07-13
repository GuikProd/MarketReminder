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

namespace App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces;

/**
 * Interface StockTagsDTOInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockTagsDTOInterface
{
    /**
     * StockTagsDTOInterface constructor.
     *
     * @param array $tags
     */
    public function __construct(array $tags = []);
}