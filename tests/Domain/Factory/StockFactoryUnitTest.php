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

namespace App\Tests\Domain\Factory;

use App\Domain\Factory\Interfaces\StockFactoryInterface;
use App\Domain\Factory\StockFactory;
use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockCreationDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class StockFactoryUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockFactoryUnitTest extends TestCase
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

    public function testItImplements()
    {
        $factory = new StockFactory();

        static::assertInstanceOf(StockFactoryInterface::class, $factory);
    }

    /**
     * @throws \Exception
     */
    public function testItCreateFromDTO()
    {
        $dto = new StockCreationDTO('', '');

        $factory = new StockFactory();

        $stock = $factory->createFromUI($dto, $this->stockOwner);

        static::assertInstanceOf(StockInterface::class, $stock);
    }

    /**
     * @throws \Exception
     */
    public function testItCreateFromRawData()
    {
        $factory = new StockFactory();

        $stock = $factory->createFromData('', '', $this->stockOwner);

        static::assertInstanceOf(StockInterface::class, $stock);
    }
}
