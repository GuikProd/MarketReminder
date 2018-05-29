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

namespace App\Tests\Domain\Event\Image;

use App\Application\Event\Image\ImageUpdatedEvent;
use App\Application\Event\Image\Interfaces\ImageUpdatedEventInterface;
use App\Domain\Models\Interfaces\ImageInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageUpdatedEventTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ImageUpdatedEventTest extends TestCase
{
    public function testItImplements()
    {
        $imageMock = $this->createMock(ImageInterface::class);

        $imageUpdatedEvent = new ImageUpdatedEvent($imageMock);

        static::assertInstanceOf(
            ImageUpdatedEventInterface::class,
            $imageUpdatedEvent
        );
    }

    public function testEventNameIsDefined()
    {
        $imageMock = $this->createMock(ImageInterface::class);

        $imageUpdatedEvent = new ImageUpdatedEvent($imageMock);

        static::assertSame(
            'image.updated',
            $imageUpdatedEvent::NAME
        );
    }

    public function testImageIsReturned()
    {
        $imageMock = $this->createMock(ImageInterface::class);

        $imageUpdatedEvent = new ImageUpdatedEvent($imageMock);

        static::assertInstanceOf(
            ImageInterface::class,
            $imageUpdatedEvent->getImage()
        );
    }
}
