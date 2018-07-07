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

namespace App\UI\Form\Type\Stock\Interfaces;

use App\UI\Form\Subscriber\Interfaces\StockItemCreationSubscriberInterface;

/**
 * Interface StockItemCreationTypeInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockItemCreationTypeInterface
{
    /**
     * StockItemCreationTypeInterface constructor.
     *
     * @param StockItemCreationSubscriberInterface $stockItemCreationSubscriber
     */
    public function __construct(StockItemCreationSubscriberInterface $stockItemCreationSubscriber);
}
