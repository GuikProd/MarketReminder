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

namespace App\Domain\UseCase\Dashboard\StockCreation\DTO;

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;

/**
 * Class StockItemCreationDTO.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemCreationDTO implements StockItemCreationDTOInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $status;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var float
     */
    public $withoutTaxesPrice;

    /**
     * @var float
     */
    public $withTaxesPrice;

    /**
     * @var
     */
    public $type;

    /**
     * @var \DateTimeImmutable|null
     */
    public $limitUsageDate = null;

    /**
     * @var \DateTimeImmutable|null
     */
    public $limitConsumptionDate = null;

    /**
     * @var \DateTimeImmutable|null
     */
    public $limitOptimalUsageDate = null;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $name = null,
        string $status = null,
        int $quantity = null,
        float $withoutTaxesPrice = null,
        float $withTaxesPrice = null,
        string $type = null,
        \DateTimeImmutable $limitUsageDate = null,
        \DateTimeImmutable $limitConsumptionDate = null,
        \DateTimeImmutable $limitOptimalUsageDate = null
    ) {
        $this->name = $name;
        $this->status = $status;
        $this->quantity = $quantity;
        $this->withoutTaxesPrice = $withoutTaxesPrice;
        $this->withTaxesPrice = $withTaxesPrice;
        $this->limitUsageDate = $limitUsageDate;
        $this->limitConsumptionDate = $limitConsumptionDate;
        $this->limitOptimalUsageDate = $limitOptimalUsageDate;
    }
}
