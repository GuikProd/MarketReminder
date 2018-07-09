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

namespace App\Domain\Models\Interfaces;

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;

/**
 * Interface StockItemsInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockItemsInterface
{
    /**
     * StockItemsInterface constructor.
     *
     * @param StockItemCreationDTOInterface $itemCreationDTO
     */
    public function __construct(StockItemCreationDTOInterface $itemCreationDTO);

    /**
     * @param StockInterface $stock
     */
    public function setStock(StockInterface $stock): void;
}
