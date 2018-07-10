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

namespace App\Tests\Domain\Builder;

use App\Domain\Builder\Interfaces\StockItemsBuilderInterface;
use App\Domain\Builder\StockItemsBuilder;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockItemCreationDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class StockItemsBuilderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemsBuilderUnitTest extends TestCase
{
    /**
     * @var StockItemCreationDTOInterface|null
     */
    private $stockItemDTO = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->stockItemDTO = new StockItemCreationDTO('', '', 1, 0.0, 0.0, '');
    }

    public function testItImplements()
    {
        $builder = new StockItemsBuilder();

        static::assertInstanceOf(
            StockItemsBuilderInterface::class,
            $builder
        );
    }

    public function testItBuild()
    {
        $builder = new StockItemsBuilder();

        $builder->createFromUI($this->stockItemDTO);

        static::assertCount(1, $builder->getStockItems());
    }
}
