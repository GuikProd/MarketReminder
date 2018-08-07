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
     * @var UserInterface|null
     */
    private $stockOwner = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->stockOwner = $this->createMock(UserInterface::class);
    }

    /**
     * @throws \Exception
     */
    public function testItImplements()
    {
        $stock = new Stock('', '', $this->stockOwner);

        static::assertInstanceOf(StockInterface::class, $stock);
    }

    /**
     * @throws \Exception
     */
    public function testItReceiveData()
    {
        $stock = new Stock('', '', $this->stockOwner);

        static::assertInstanceOf(UuidInterface::class, $stock->getId());
        static::assertSame('', $stock->getTitle());
    }

    /**
     * @throws \Exception
     */
    public function testItCouldReceiveItems()
    {
        $itemMock = $this->createMock(StockItemsInterface::class);

        $stock = new Stock('', '', $this->stockOwner, [], [$itemMock]);

        static::assertCount(1, $stock->getItems());
    }
}
