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

namespace App\Tests\UI\Form\Extension;

use App\UI\Form\Extension\StockTagsTypeExtension;
use App\UI\Form\Type\Stock\StockTagsType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormTypeExtensionInterface;

/**
 * Class StockTagsTypeExtensionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockTagsTypeExtensionUnitTest extends TestCase
{
    public function testConfiguration()
    {
        $extension = new StockTagsTypeExtension();

        static::assertInstanceOf(
            FormTypeExtensionInterface::class,
            $extension
        );
        static::assertSame(
            StockTagsType::class,
            $extension->getExtendedType()
        );
    }
}
