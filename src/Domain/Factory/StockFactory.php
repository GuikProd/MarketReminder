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

namespace App\Domain\Factory;

use App\Domain\Factory\Interfaces\StockFactoryInterface;
use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\Stock;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;

/**
 * Class StockFactory.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockFactory implements StockFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function createFromUI(
        StockCreationDTOInterface $stockCreationDTO,
        UserInterface $owner,
        array $tags = [],
        array $stockItems = []
    ): StockInterface {
        return new Stock(
            $stockCreationDTO->title,
            $stockCreationDTO->status,
            $owner,
            $tags,
            $stockItems
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function createFromData(
        string $title,
        string $status,
        UserInterface $owner,
        array $tags = [],
        array $stockItems = []
    ): StockInterface {
        return new Stock($title, $status, $owner, $tags, $stockItems);
    }
}
