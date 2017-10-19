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
use App\Models\Image;
use App\Models\Stock;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserTest extends TestCase
{
    public function testInstantiation()
    {
        $user = new User();

        $user->setFirstname('Harry');
        $user->setLastname('Potter');
        $user->setUsername('HP');
        $user->setEmail('hp@gmail.com');
        $user->setPlainPassword('Ie1FDLHPP');
        $user->setPassword('Ie1FDLHPP');
        $user->setRole('ROLE_WIZARD');
        $user->setCreationDate(new \DateTime('2017-03-21'));
        $user->setValidationDate(new \DateTime('2017-03-21'));
        $user->setValidated(true);
        $user->setActive(true);
        $user->setApiToken('a5d4a94d498zx59z1xas5s5s2sa47s7+s4+as4s49');

        static::assertNull($user->getId());
        static::assertEquals('Harry', $user->getFirstname());
        static::assertEquals('Potter', $user->getLastname());
        static::assertEquals('HP', $user->getUsername());
        static::assertEquals('hp@gmail.com', $user->getEmail());
        static::assertEquals('Ie1FDLHPP', $user->getPlainPassword());
        static::assertEquals('Ie1FDLHPP', $user->getPassword());
        static::assertContains('ROLE_WIZARD', $user->getRoles());
        static::assertEquals(new \DateTime('2017-03-21'), $user->getCreationDate());
        static::assertEquals(new \DateTime('2017-03-21'), $user->getValidationDate());
        static::assertTrue($user->getValidated());
        static::assertTrue($user->getActive());
        static::assertEquals('a5d4a94d498zx59z1xas5s5s2sa47s7+s4+as4s49', $user->getApiToken());
    }

    public function testStockRelation()
    {
        $user = new User();

        $user->setFirstname('Harry');
        $user->setLastname('Potter');
        $user->setUsername('HP');
        $user->setEmail('hp@gmail.com');
        $user->setPlainPassword('Ie1FDLHPP');
        $user->setPassword('Ie1FDLHPP');
        $user->setRole('ROLE_WIZARD');
        $user->setCreationDate(new \DateTime('2017-03-21'));
        $user->setValidationDate(new \DateTime('2017-03-21'));
        $user->setValidated(true);
        $user->setActive(true);
        $user->setApiToken('a5d4a94d498zx59z1xas5s5s2sa47s7+s4+as4s49');

        $stock = $this->createMock(Stock::class);
        $stock->method('getId')
              ->willReturn(0);

        $user->addStock($stock);

        static::assertEquals($stock, $user->getStocks()->get(0));

        $user->removeStock($stock);

        static::assertEmpty($user->getStocks());
    }

    public function testImageRelation()
    {
        $user = new User();

        $user->setFirstname('Harry');
        $user->setLastname('Potter');
        $user->setUsername('HP');
        $user->setEmail('hp@gmail.com');
        $user->setPlainPassword('Ie1FDLHPP');
        $user->setPassword('Ie1FDLHPP');
        $user->setRole('ROLE_WIZARD');
        $user->setCreationDate(new \DateTime('2017-03-21'));
        $user->setValidationDate(new \DateTime('2017-03-21'));
        $user->setValidated(true);
        $user->setActive(true);
        $user->setApiToken('a5d4a94d498zx59z1xas5s5s2sa47s7+s4+as4s49');

        $image = $this->createMock(Image::class);
        $image->method('getId')
              ->willReturn(0);

        $user->setImage($image);

        static::assertEquals($image, $user->getImage());
    }
}
