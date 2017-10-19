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

use App\Models\User;
use App\Models\Stock;
use App\Models\Products;
use PHPUnit\Framework\TestCase;

/**
 * Class StockTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class StockTest extends TestCase
{
    public function testInstantiation()
    {
        $stock = new Stock();

        $stock->setCreationDate(new \DateTime('2017-03-21'));
        $stock->setModificationDate(new \DateTime('2017-03-21'));
        $stock->setStatus('In use');

        static::assertNull($stock->getId());
        static::assertEquals(new \DateTime('2017-03-21'), $stock->getCreationDate());
        static::assertEquals(new \DateTime('2017-03-21'), $stock->getModificationDate());
        static::assertEquals('In use', $stock->getStatus());
    }

    public function testUserRelation()
    {
        $stock = new Stock();

        $stock->setCreationDate(new \DateTime('2017-03-21'));
        $stock->setModificationDate(new \DateTime('2017-03-21'));
        $stock->setStatus('In use');

        $user = $this->createMock(User::class);
        $user->method('getId')
             ->willReturn(0);

        $stock->setUser($user);

        static::assertEquals($user, $stock->getUser());
    }

    public function testProductsRelation()
    {
        $stock = new Stock();

        $stock->setCreationDate(new \DateTime('2017-03-21'));
        $stock->setModificationDate(new \DateTime('2017-03-21'));
        $stock->setStatus('In use');

        $products = $this->createMock(Products::class);
        $products->method('getId')
                 ->willReturn(0);

        $stock->addProduct($products);

        static::assertEquals($products, $stock->getProducts()->get(0));

        $stock->removeProduct($products);

        static::assertEmpty($stock->getProducts());
    }
}
