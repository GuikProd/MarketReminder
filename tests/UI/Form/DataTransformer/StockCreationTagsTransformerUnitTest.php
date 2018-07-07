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

namespace App\Tests\UI\Form\DataTransformer;

use App\UI\Form\DataTransformer\Interfaces\StockCreationTagsTransformerInterface;
use App\UI\Form\DataTransformer\StockCreationTagsTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class StockCreationTagsTransformerUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationTagsTransformerUnitTest extends TestCase
{
    public function testItExtendsAndImplements()
    {
        $dataTransformer = new StockCreationTagsTransformer();

        static::assertInstanceOf(
            StockCreationTagsTransformerInterface::class,
            $dataTransformer
        );
        static::assertInstanceOf(
            DataTransformerInterface::class,
            $dataTransformer
        );
    }

    public function testItTransformData()
    {
        $dataTransformer = new StockCreationTagsTransformer();

        static::assertSame(
            'test, toto, content',
            $dataTransformer->transform(['test', 'toto', 'content'])
        );
    }

    public function testItReverseTransformData()
    {
        $dataTransformer = new StockCreationTagsTransformer();

        static::assertSame(
            ['test', 'toto', 'content'],
            $dataTransformer->reverseTransform('test, toto, content')
        );
    }
}
