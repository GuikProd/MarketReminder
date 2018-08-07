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

namespace App\Domain\Repository\Interfaces;

use App\Domain\Models\Interfaces\StockInterface;

/**
 * Interface StockRepositoryInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockRepositoryInterface
{
    /**
     * @return array
     */
    public function getAllTricks(): array;

    /**
     * @param StockInterface $stock
     */
    public function save(StockInterface $stock): void;
}
