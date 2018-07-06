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

namespace App\Tests\Application\Validator;

use App\Application\Validator\ImageContent;
use App\Application\Validator\ImageContentValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

/**
 * Class ImageContentUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ImageContentUnitTest extends TestCase
{
    public function testItExtends()
    {
        $imageContent = new ImageContent();

        static::assertInstanceOf(
            Constraint::class,
            $imageContent
        );
    }

    public function testViolationMessage()
    {
        $imageContent = new ImageContent();

        $imageContent->message = 'Toto';

        static::assertSame('Toto', $imageContent->message);
    }

    public function testIsValidatedBy()
    {
        $imageContent = new ImageContent();

        static::assertSame(
            ImageContentValidator::class,
            $imageContent->validatedBy()
        );
    }
}
