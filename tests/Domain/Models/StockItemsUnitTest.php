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

namespace App\Tests\Domain\Models;

use App\Domain\Models\Interfaces\StockItemsInterface;
use App\Domain\Models\StockItems;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class StockItemsUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemsUnitTest extends TestCase
{
    /**
     * @var StockCreationDTOInterface|null
     */
    private $stockItemDTO = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->stockItemDTO = $this->createMock(StockItemCreationDTOInterface::class);
    }

    public function testItImplements()
    {
        $items = new StockItems($this->stockItemDTO);

        static::assertInstanceOf(
            StockItemsInterface::class,
            $items
        );
    }
}
