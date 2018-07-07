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
 * Interface StockTagsTypeInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockTagsTypeInterface
{
    /**
     * StockTagsTypeInterface constructor.
     *
     * @param StockCreationTagsTransformerInterface $tagsTransformer
     */
    public function __construct(StockCreationTagsTransformerInterface $tagsTransformer);
}
