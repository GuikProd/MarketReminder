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
use PHPUnit\Framework\TestCase;

/**
 * Class ImageTest
 *
 * @author Guillaume Loulier <contact]guillaumeloulier.fr>
 */
class ImageTest extends TestCase
{
    public function testInstantiation()
    {
        $image = new Image();

        $image->setCreationDate(new \DateTime('2017-03-21'));
        $image->setModificationDate(new \DateTime('2017-03-21'));
        $image->setAlt('New Image');
        $image->setUrl('http://localhost/public/images/new_image.png');

        static::assertEquals(new \DateTime('2017-03-21'), $image->getCreationDate());
        static::assertEquals(new \DateTime('2017-03-21'), $image->getModificationDate());
        static::assertEquals('New Image', $image->getAlt());
        static::assertEquals('http://localhost/public/images/new_image.png', $image->getUrl());
    }

    public function testUserRelation()
    {
        $image = new Image();

        $image->setCreationDate(new \DateTime('2017-03-21'));
        $image->setAlt('New Image');
        $image->setUrl('http://localhost/public/images/new_image.png');

        $user = $this->createMock(User::class);
        $user->method('getId')
             ->willReturn(0);

        $image->setUser($user);

        static::assertEquals($user, $image->getUser());
    }
}
