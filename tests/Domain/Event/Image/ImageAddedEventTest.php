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

namespace App\Tests\Domain\Event\Image;

use PHPUnit\Framework\TestCase;
use App\Domain\Event\Image\ImageAddedEvent;
use App\Domain\Models\Interfaces\ImageInterface;

/**
 * Class ImageAddedEventTest;.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageAddedEventTest extends TestCase
{
    public function testGetterReturn()
    {
        $imageMock = $this->createMock(ImageInterface::class);

        $imageAddedEvent = new ImageAddedEvent($imageMock);

        static::assertInstanceOf(
            ImageInterface::class,
            $imageAddedEvent->getImage()
        );
    }
}
