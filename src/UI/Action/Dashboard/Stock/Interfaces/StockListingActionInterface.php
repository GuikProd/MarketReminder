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

namespace App\UI\Action\Dashboard\Stock\Interfaces;

use App\Domain\Repository\Interfaces\StockRepositoryInterface;

/**
 * Interface StockListingActionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockListingActionInterface
{
    /**
     * StockListingActionInterface constructor.
     *
     * @param StockRepositoryInterface $repository
     */
    public function __construct(StockRepositoryInterface $repository);
}
