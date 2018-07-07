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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;

/**
 * Class StockCreationDTO.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationDTO implements StockCreationDTOInterface
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $status;

    /**
     * @var array
     */
    public $tags = [];

    /**
     * @var StockItemCreationDTOInterface[]
     */
    public $stockItems = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $title,
        string $status,
        array $tags = [],
        array $stockItems = []
    ) {
        $this->title = $title;
        $this->status = $status;
        $this->tags = $tags;
        $this->stockItems = $stockItems;
    }
}
