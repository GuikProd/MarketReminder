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

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\StockItemsBuilderInterface;
use App\Domain\Models\Interfaces\StockItemsInterface;
use App\Domain\Models\StockItems;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;

/**
 * Class StockItemsBuilder.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemsBuilder implements StockItemsBuilderInterface
{
    /**
     * @var StockItemsInterface[]
     */
    private $stockItems = [];

    /**
     * {@inheritdoc}
     */
    public function createFromUI(StockItemCreationDTOInterface $itemCreationDTO): void
    {
        $this->stockItems[] = new StockItems($itemCreationDTO);
    }

    /**
     * {@inheritdoc}
     */
    public function getStockItems(): array
    {
        return $this->stockItems;
    }
}
