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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockTagsDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockTagsDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class StockTagsDTOUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockTagsDTOUnitTest extends TestCase
{
    public function testItImplements()
    {
        $dto = new StockTagsDTO();

        static::assertInstanceOf(
            StockTagsDTOInterface::class,
            $dto
        );
    }

    public function testItHandleData()
    {
        $dto = new StockTagsDTO();

        static::assertCount(0, $dto->tags);
    }
}
