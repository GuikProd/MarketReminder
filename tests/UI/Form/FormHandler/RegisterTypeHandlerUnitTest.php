<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>s
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\UI\Form\FormHandler;

use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\UseCase\UserRegistration\DTO\UserRegistrationDTO;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStoragePersisterHelperInterface;
use App\UI\Form\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use App\UI\Form\FormHandler\RegisterTypeHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterTypeHandlerUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandlerUnitTest extends TestCase
{
    /**
     * @var CloudStoragePersisterHelperInterface
     */
    private $cloudStoragePersister;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EncoderFactoryInterface
     */
    private $passwordEncoderFactory;

    /**
     * @var ImageBuilderInterface
     */
    private $imageBuilder;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelper;

    /**
     * @var ImageRetrieverHelperInterface
     */
    private $imageRetrieverHelper;

    /**
     * @var UserBuilderInterface
     */
    private $userBuilder;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudStoragePersister = $this->createMock(CloudStoragePersisterHelperInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->passwordEncoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->imageBuilder = $this->createMock(ImageBuilderInterface::class);
        $this->imageUploaderHelper = $this->createMock(ImageUploaderHelperInterface::class);
        $this->imageRetrieverHelper = $this->createMock(ImageRetrieverHelperInterface::class);
        $this->userBuilder = $this->createMock(UserBuilderInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->passwordEncoderFactory->method('getEncoder')->willReturn(new BCryptPasswordEncoder(13));
        $this->validator->method('validate')->willReturn([]);
    }

    public function testItImplements()
    {
        $registerTypeHandler = new RegisterTypeHandler(
            $this->cloudStoragePersister,
            $this->eventDispatcher,
            $this->passwordEncoderFactory,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->imageRetrieverHelper,
            $this->userBuilder,
            $this->userRepository,
            $this->validator
        );

        static::assertInstanceOf(
            RegisterTypeHandlerInterface::class,
            $registerTypeHandler
        );
    }

    public function testWrongHandlingProcess()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $registerTypeHandler = new RegisterTypeHandler(
            $this->cloudStoragePersister,
            $this->eventDispatcher,
            $this->passwordEncoderFactory,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->imageRetrieverHelper,
            $this->userBuilder,
            $this->userRepository,
            $this->validator
        );

        $formInterfaceMock->method('isValid')->willReturn(false);
        $formInterfaceMock->method('isSubmitted')->willReturn(false);

        static::assertFalse(
            $registerTypeHandler->handle($formInterfaceMock)
        );
    }

    public function testRightHandlingProcessWithoutImage()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $fileTypeMock = $this->createMock(FormInterface::class);

        $userRegistrationDTOMock = new UserRegistrationDTO(
            'Toto',
            'toto@gmail.com',
            'Ie1FDLTOTO',
            'da248z614d2az68d'
        );

        $registerTypeHandler = new RegisterTypeHandler(
            $this->cloudStoragePersister,
            $this->eventDispatcher,
            $this->passwordEncoderFactory,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->imageRetrieverHelper,
            $this->userBuilder,
            $this->userRepository,
            $this->validator
        );

        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userRegistrationDTOMock);
        $fileTypeMock->method('getData')->willReturn(null);

        static::assertTrue(
            $registerTypeHandler->handle($formInterfaceMock)
        );
    }

    public function testRightHandlingProcessWithImage()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $uploadedImage = $this->createMock(\SplFileInfo::class);
        $uploadedImage->method('getBasename')->willReturn('/tmp/hdhzdzdndjdzndnzd');

        $userRegistrationDTOMock = new UserRegistrationDTO(
            'Toto',
            'toto@gmail.com',
            'Ie1FDLTOTO',
            'da248z614d2az68d',
            $uploadedImage
        );

        $registerTypeHandler = new RegisterTypeHandler(
            $this->cloudStoragePersister,
            $this->eventDispatcher,
            $this->passwordEncoderFactory,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->imageRetrieverHelper,
            $this->userBuilder,
            $this->userRepository,
            $this->validator
        );

        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userRegistrationDTOMock);

        static::assertTrue(
            $registerTypeHandler->handle($formInterfaceMock)
        );
    }
}
