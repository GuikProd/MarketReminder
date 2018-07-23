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

use App\UI\Form\DataTransformer\Interfaces\StockCreationTagsTransformerInterface;

/**
 * Interface StockCreationTypeInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockCreationTypeInterface
{
    /**
     * StockCreationTypeInterface constructor.
     *
     * @param StockCreationTagsTransformerInterface $stockCreationTagsTransformer
     */
    public function __construct(StockCreationTagsTransformerInterface $stockCreationTagsTransformer);
}
