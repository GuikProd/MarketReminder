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

use App\Domain\Models\Interfaces\StockItemsInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class StockItems.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class StockItems implements StockItemsInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $status;

    /**
     * @var
     */
    private $quantity;

    /**
     * @var float
     */
    private $withoutTaxesPrice = 0.0;

    /**
     * @var float
     */
    private $withTaxesPrice = 0.0;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int|null
     */
    private $limitUsageDate = null;

    /**
     * @var int|null
     */
    private $limitConsumptionDate = null;

    /**
     * @var null
     */
    private $limitOptimalUsageDate = null;

    /**
     * {@inheritdoc}
     */
    public function __construct(StockItemCreationDTOInterface $itemCreationDTO)
    {
        $this->id = Uuid::uuid4();
        $this->name = $itemCreationDTO->name;
        $this->status = $itemCreationDTO->status;
        $this->quantity = $itemCreationDTO->quantity;
        $this->withoutTaxesPrice = $itemCreationDTO->withoutTaxesPrice;
        $this->withTaxesPrice = $itemCreationDTO->withTaxesPrice;
        $this->type = $itemCreationDTO->type;
        $this->limitUsageDate = $itemCreationDTO->limitUsageDate;
        $this->limitConsumptionDate = $itemCreationDTO->limitConsumptionDate;
        $this->limitOptimalUsageDate = $itemCreationDTO->limitOptimalUsageDate;
    }
}
