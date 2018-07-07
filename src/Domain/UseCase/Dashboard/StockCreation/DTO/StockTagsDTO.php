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

namespace App\Domain\UseCase\Dashboard\StockCreation\DTO;

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockTagsDTOInterface;

/**
 * Class StockTagsDTO.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockTagsDTO implements StockTagsDTOInterface
{
    /**
     * @var array
     */
    public $tags = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $tags = [])
    {
        $this->tags = $tags;
    }
}
