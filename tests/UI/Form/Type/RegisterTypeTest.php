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
use App\Domain\UseCase\UserRegistration\DTO\Interfaces\UserRegistrationDTOInterface;
use App\UI\Form\Type\ImageUploadType;
use App\UI\Form\Type\RegisterType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterTypeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeTest extends TypeTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

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
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->validator
             ->method('validate')
             ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
             ->method('getMetadataFor')
             ->will($this->returnValue(new ClassMetadata(Form::class)));

        $imageUploadType = new ImageUploadType($this->imageUploadSubscriber);

        return array(
            new PreloadedExtension([$imageUploadType], []),
            new ValidatorExtension($this->validator)
        );
    }

    public function testDataSubmission()
    {
        $registerType = $this->factory->create(RegisterType::class);

        static::assertTrue(
            $registerType->isSynchronized()
        );

        static::assertInstanceOf(
            UserRegistrationDTOInterface::class,
            $registerType->getData()
        );
    }
}
