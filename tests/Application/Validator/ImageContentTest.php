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

namespace App\Tests\Application\Validator;

use App\Application\Validator\ImageContent;
use App\Application\Validator\Interfaces\ImageContentValidatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

/**
 * Class ImageContentTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageContentTest extends TestCase
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
            ImageContentValidatorInterface::class,
            $imageContent->validatedBy()
        );
    }
}
