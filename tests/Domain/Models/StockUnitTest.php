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

use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\StockItemsInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\Stock;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockCreationDTO;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Class StockUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockUnitTest extends TestCase
{
    /**
     * @var StockCreationDTOInterface|null
     */
    private $stockCreationDTO = null;

    /**
     * @var UserInterface|null
     */
    private $stockOwner = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $stockItemMock = $this->createMock(StockItemsInterface::class);

        $this->stockCreationDTO = new StockCreationDTO('', '', [], [$stockItemMock]);
        $this->stockOwner = $this->createMock(UserInterface::class);
    }

    public function testItImplements()
    {
        $stock = new Stock(
            $this->stockCreationDTO,
            $this->stockOwner
        );

        static::assertInstanceOf(
            StockInterface::class,
            $stock
        );
    }

    public function testItReceiveData()
    {
        $stock = new Stock(
            $this->stockCreationDTO,
            $this->stockOwner
        );

        static::assertInstanceOf(
            UuidInterface::class,
            $stock->getId()
        );
        static::assertSame(
            '',
            $stock->getTitle()
        );
    }

    public function testItCouldReceiveItems()
    {
        $itemMock = $this->createMock(StockItemsInterface::class);

        $stock = new Stock(
            $this->stockCreationDTO,
            $this->stockOwner,
            [$itemMock]
        );

        static::assertCount(1, $stock->getItems());
    }
}
