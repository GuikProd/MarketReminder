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

namespace App\Domain\Models;

use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\StockItemsInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class StockItems.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItems implements StockItemsInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var StockInterface
     */
    private $stock;

    /**
     * {@inheritdoc}
     */
    public function __construct(StockItemCreationDTOInterface $itemCreationDTO)
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * {@inheritdoc}
     */
    public function setStock(StockInterface $stock): void
    {
        $this->stock = $stock;
    }
}
