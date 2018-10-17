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

namespace App\Domain\Factory\Interfaces;

use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;

/**
 * Interface StockFactoryInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockFactoryInterface
{
    /**
     * @param StockCreationDTOInterface $stockCreationDTO
     * @param UserInterface                 $owner
     * @param array                           $tags
     * @param array                           $stockItems
     *
     * @return StockInterface
     */
    public function createFromUI(
        StockCreationDTOInterface $stockCreationDTO,
        UserInterface $owner,
        array $tags = [],
        array $stockItems = []
    ): StockInterface;

    /**
     * @param string           $title
     * @param string           $status
     * @param UserInterface $owner
     * @param array           $tags
     * @param array           $stockItems
     *
     * @return StockInterface
     */
    public function createFromData(
        string $title,
        string $status,
        UserInterface $owner,
        array $tags = [],
        array $stockItems = []
    ): StockInterface;
}