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

use App\Application\Validator\ImageContentValidator;
use App\Application\Validator\Interfaces\ImageContentValidatorInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;
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
            $this->createMock(CloudVisionDescriberHelperInterface::class),
            $this->createMock(TranslatorInterface::class)

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
            'cloudVisionAnalyser',
            ImageContentValidator::class
        );

        static::assertClassHasAttribute(
            'cloudVisionDescriber',
            ImageContentValidator::class
        );
    }
}
