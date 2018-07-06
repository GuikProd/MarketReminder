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

namespace App\Tests\Domain\UseCase\Dashboard\StockCreation\DTO;

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockCreationDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class StockCreationDTOUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationDTOUnitTest extends TestCase
{
    public function testItImplements()
    {
        $dto = new StockCreationDTO('', '', []);

        static::assertInstanceOf(
            StockCreationDTOInterface::class,
            $dto
        );
    }

    public function testItReceiveData()
    {
        $dto = new StockCreationDTO('', '', []);

        static::assertSame('', $dto->title);
        static::assertSame('', $dto->status);
    }
}
