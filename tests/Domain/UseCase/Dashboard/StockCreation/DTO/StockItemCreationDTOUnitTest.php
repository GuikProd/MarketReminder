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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockItemCreationDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class StockItemCreationDTOUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemCreationDTOUnitTest extends TestCase
{
    public function testItImplements()
    {
        $dto = new StockItemCreationDTO('', '', 0, 0.0, 0.0, '');

        static::assertInstanceOf(
            StockItemCreationDTOInterface::class,
            $dto
        );
    }
}
