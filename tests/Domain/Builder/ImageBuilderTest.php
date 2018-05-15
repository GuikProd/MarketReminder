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

namespace App\Tests\Domain\Builder;

use App\Domain\Builder\ImageBuilder;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Models\Interfaces\ImageInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageBuilderTest.
 *
 * @author Guillaume Louliefr <contact@guillaumeloulier.fr>
 */
class ImageBuilderTest extends TestCase
{
    public function testInstanceOf()
    {
        $imageBuilder = new ImageBuilder();

        static::assertInstanceOf(
            ImageBuilderInterface::class,
            $imageBuilder
        );
    }

    public function testImageBuild()
    {
        $imageBuilder = new ImageBuilder();

        $imageBuilder->build(
            'toto',
            'toto.png',
            'http://test.com/images/uploads/toto.png'
        );

        static::assertInstanceOf(
            ImageInterface::class,
            $imageBuilder->getImage()
        );
        static::assertSame('toto', $imageBuilder->getImage()->getAlt());
        static::assertSame('toto.png', $imageBuilder->getImage()->getFilename());
        static::assertSame(
            'http://test.com/images/uploads/toto.png',
            $imageBuilder->getImage()->getPublicUrl()
        );
    }
}
