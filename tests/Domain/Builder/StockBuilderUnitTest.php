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

use App\Domain\Builder\Interfaces\StockBuilderInterface;
use App\Domain\Builder\StockBuilder;
use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockCreationDTO;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockTagsDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class StockBuilderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockBuilderUnitTest extends TestCase
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
        $builder = new StockBuilder();

        static::assertInstanceOf(
            StockBuilderInterface::class,
            $builder
        );
    }

    public function testItCreate()
    {
        $stockTagsDTOMock = new StockTagsDTO([]);

        $dto = new StockCreationDTO('', '', [$stockTagsDTOMock], []);

        $builder = new StockBuilder();

        $builder->createFromUI($dto, $this->stockOwner);

        static::assertInstanceOf(
            StockInterface::class,
            $builder->getStock()
        );
    }
}
