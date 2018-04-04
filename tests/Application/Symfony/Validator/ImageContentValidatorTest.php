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

namespace App\Tests\Application\Symfony\Validator;

use App\Application\Helper\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Application\Helper\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Application\Symfony\Validator\ImageContentValidator;
use App\Application\Symfony\Validator\Interfaces\ImageContentValidatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ImageContentValidatorTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageContentValidatorTest extends TestCase
{
    public function testItExtendsAndImplements()
    {
        $imageContentValidator = new ImageContentValidator(
            $this->createMock(CloudVisionAnalyserHelperInterface::class),
            $this->createMock(CloudVisionDescriberHelperInterface::class)

        );

        static::assertInstanceOf(
            ConstraintValidator::class,
            $imageContentValidator
        );

        static::assertInstanceOf(
            ImageContentValidatorInterface::class,
            $imageContentValidator
        );

        static::assertClassHasAttribute(
            'cloudVisionAnalyserHelper',
            ImageContentValidator::class
        );

        static::assertClassHasAttribute(
            'cloudVisionDescriberHelper',
            ImageContentValidator::class
        );
    }
}
