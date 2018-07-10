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

namespace App\Domain\Builder\Interfaces;

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;

/**
 * Interface StockItemsBuilderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockItemsBuilderInterface
{
    /**
     * @param StockItemCreationDTOInterface $itemCreationDTO
     */
    public function createFromUI(StockItemCreationDTOInterface $itemCreationDTO): void;

    /**
     * @return array
     */
    public function getStockItems(): array;
}
