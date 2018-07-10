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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * Interface StockInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockInterface
{
    /**
     * StockInterface constructor.
     *
     * @param StockCreationDTOInterface $stockCreationDTO
     * @param UserInterface $owner
     */
    public function __construct(
        StockCreationDTOInterface $stockCreationDTO,
        UserInterface $owner
    );

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return array
     */
    public function getStatus(): array;

    /**
     * @param array $items
     */
    public function addItems(array $items): void;

    /**
     * @return array
     */
    public function getItems(): array;
}
