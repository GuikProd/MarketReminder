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
 * Interface StockItemCreationDTOInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockItemCreationDTOInterface
{
    /**
     * StockItemCreationDTOInterface constructor.
     *
     * @param string $name
     * @param string $status
     * @param int $quantity
     * @param float $withoutTaxesPrice
     * @param float $withTaxesPrice
     * @param string $type
     * @param \DateTimeImmutable|null $limitUsageDate
     * @param \DateTimeImmutable|null $limitConsumptionDate
     * @param \DateTimeImmutable|null $limitOptimalUsageDate
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
    );
}
