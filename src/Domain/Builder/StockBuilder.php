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

use App\Domain\Builder\Interfaces\StockBuilderInterface;
use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\Stock;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;

/**
 * Class StockBuilder.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockBuilder implements StockBuilderInterface
{
    /**
     * @var StockInterface
     */
    private $stock;

    /**
     * {@inheritdoc}
     */
    public function createFromUI(
        StockCreationDTOInterface $stockCreationDTO,
        UserInterface $owner,
        array $items = []
    ): void {

        $this->stock = new Stock($stockCreationDTO, $owner, $items ?: []);
    }

    /**
     * {@inheritdoc}
     */
    public function getStock(): StockInterface
    {
        return $this->stock;
    }
}
