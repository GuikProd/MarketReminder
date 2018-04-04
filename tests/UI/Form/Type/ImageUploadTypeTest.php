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

use App\Application\Helper\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Application\Helper\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Application\Symfony\Subscriber\ImageUploadSubscriber;
use App\Application\Symfony\Subscriber\Interfaces\ImageUploadSubscriberInterface;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\UseCase\UserRegistration\DTO\Interfaces\ImageRegistrationDTOInterface;
use App\UI\Form\Type\ImageUploadType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ImageUploadTypeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadTypeTest extends TypeTestCase
{
    /**
     * @var ImageUploadSubscriberInterface
     */
    private $imageUploadSubscriber;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->imageUploadSubscriber = new ImageUploadSubscriber(
            $this->createMock(TranslatorInterface::class),
            $this->createMock(ImageBuilderInterface::class),
            $this->createMock(ImageUploaderHelperInterface::class),
            $this->createMock(CloudVisionAnalyserHelperInterface::class),
            $this->createMock(ImageRetrieverHelperInterface::class),
            $this->createMock(CloudVisionDescriberHelperInterface::class)
        );

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        $imageUploadType = new ImageUploadType($this->imageUploadSubscriber);

        return [
            new PreloadedExtension(
                array($imageUploadType), array()
            )
        ];
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
