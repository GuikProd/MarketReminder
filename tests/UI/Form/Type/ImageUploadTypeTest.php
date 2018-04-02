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

namespace App\Tests\UI\Form\Type;

use App\Domain\UseCase\UserRegistration\DTO\Interfaces\ImageRegistrationDTOInterface;
use App\UI\Form\Type\ImageUploadType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class ImageUploadTypeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadTypeTest extends TypeTestCase
{
    /**
     * @var
     */
    private $fileUploadSubscriber;

    public function setUp()
    {
    }

    public function testWrongDataIsSubmitted()
    {
        $imageForm = $this->factory->create(ImageUploadType::class);

        static::assertInstanceOf(
            FormInterface::class,
            $imageForm
        );

        static::assertArrayHasKey(
            'validation_groups',
            $imageForm->getConfig()->getOptions()
        );

        static::assertInstanceOf(
            ImageRegistrationDTOInterface::class,
            $imageForm->getData()
        );
    }
}
