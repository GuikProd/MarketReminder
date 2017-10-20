<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Models;

use App\Models\Stock;
use App\Models\Products;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductsTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProductsTest extends TestCase
{
    public function testInstantiation()
    {
        $products = new Products();

        $products->setLabel('Salade');
        $products->setEntryDate(new \DateTime('2017-03-21'));
        $products->setModificationDate(new \DateTime('2017-03-21'));
        $products->setOutDate(new \DateTime('2017-03-21'));
        $products->setQuantity(120);
        $products->setStatus('Added');
        $products->setLimiteConsumptionDate(new \DateTime('2017-03-21'));
        $products->setLimiteUsageDate(new \DateTime('2017-03-21'));

        static::assertNull($products->getId());
        static::assertEquals('Salade', $products->getLabel());
        static::assertEquals(new \DateTime('2017-03-21'), $products->getEntryDate());
        static::assertEquals(new \DateTime('2017-03-21'), $products->getModificationDate());
        static::assertEquals(new \DateTime('2017-03-21'), $products->getOutDate());
        static::assertEquals(120, $products->getQuantity());
        static::assertEquals('Added', $products->getStatus());
        static::assertEquals(new \DateTime('2017-03-21'), $products->getLimiteConsumptionDate());
        static::assertEquals(new \DateTime('2017-03-21'), $products->getLimiteUsageDate());
    }

    public function testStockRelation()
    {
        $products = new Products();

        $products->setLabel('Salade');
        $products->setEntryDate(new \DateTime('2017-03-21'));
        $products->setModificationDate(new \DateTime('2017-03-21'));
        $products->setOutDate(new \DateTime('2017-03-21'));
        $products->setQuantity(120);
        $products->setStatus('Added');
        $products->setLimiteConsumptionDate(new \DateTime('2017-03-21'));
        $products->setLimiteUsageDate(new \DateTime('2017-03-21'));

        $stock = $this->createMock(Stock::class);
        $stock->method('getId')
              ->willReturn(0);

        $products->setStock($stock);

        static::assertEquals($stock, $products->getStock());
    }
}
