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

namespace App\Domain\Builder\Interfaces;

use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;

/**
 * Interface StockBuilderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockBuilderInterface
{
    /**
     * @param StockCreationDTOInterface $stockCreationDTO
     * @param UserInterface $owner
     * @param array $items
     */
    public function createFromUI(
        StockCreationDTOInterface $stockCreationDTO,
        UserInterface $owner,
        array $items = []
    ): void;

    /**
     * @return StockInterface
     */
    public function getStock(): StockInterface;
}
