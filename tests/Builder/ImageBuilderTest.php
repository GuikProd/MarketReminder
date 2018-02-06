<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\Builder;

use App\Builder\ImageBuilder;
use PHPUnit\Framework\TestCase;
use App\Models\Interfaces\UserInterface;

/**
 * Class ImageBuilderTest;
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageBuilderTest extends TestCase
{
    public function testRegistration()
    {
        $imageBuilder = new ImageBuilder();
        $userMock = $this->createMock(UserInterface::class);
        $userMock->method('getId')
                 ->willReturn(0);

        $imageBuilder
            ->createImage()
            ->withAlt('Toto')
            ->withPublicUrl('http://storage.api.com/toto.png')
            ->withCreationDate(new \DateTime('03-02-2018'))
            ->withModificationDate(new \DateTime('03-02-2018'))
            ->withUser($userMock);

        static::assertNull($imageBuilder->getImage()->getId());
        static::assertSame('Toto', $imageBuilder->getImage()->getAlt());
        static::assertSame(0, $imageBuilder->getImage()->getUser()->getId());
        static::assertSame('http://storage.api.com/toto.png', $imageBuilder->getImage()->getUrl());
        static::assertEquals('Sat 03-02-2018 12:00:00', $imageBuilder->getImage()->getCreationDate());
        static::assertEquals('Sat 03-02-2018 12:00:00', $imageBuilder->getImage()->getModificationDate());
    }
}
