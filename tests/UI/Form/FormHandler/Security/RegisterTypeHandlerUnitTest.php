<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>s
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\UI\Form\FormHandler\Security;

use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Factory\Interfaces\UserFactoryInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\UseCase\UserRegistration\DTO\UserRegistrationDTO;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use App\UI\Form\FormHandler\Security\Interfaces\RegisterTypeHandlerInterface;
use App\UI\Form\FormHandler\Security\RegisterTypeHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterTypeHandlerUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RegisterTypeHandlerUnitTest extends TestCase
{
    /**
     * @var CloudStorageWriterHelperInterface|null
     */
    private $cloudStoragePersister = null;

    /**
     * @var EncoderFactoryInterface|null
     */
    private $passwordEncoderFactory = null;

    /**
     * @var ImageBuilderInterface|null
     */
    private $imageBuilder = null;

    /**
     * @var ImageUploaderHelperInterface|null
     */
    private $imageUploaderHelper = null;

    /**
     * @var ImageRetrieverHelperInterface|null
     */
    private $imageRetrieverHelper = null;

    /**
     * @var MessageBusInterface|null
     */
    private $messageBus = null;

    /**
     * @var UserFactoryInterface|null
     */
    private $userFactory = null;

    /**
     * @var UserRepositoryInterface|null
     */
    private $userRepository = null;

    /**
     * @var ValidatorInterface|null
     */
    private $validator = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudStoragePersister = $this->createMock(CloudStorageWriterHelperInterface::class);
        $this->passwordEncoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->imageBuilder = $this->createMock(ImageBuilderInterface::class);
        $this->imageUploaderHelper = $this->createMock(ImageUploaderHelperInterface::class);
        $this->imageRetrieverHelper = $this->createMock(ImageRetrieverHelperInterface::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->userFactory = $this->createMock(UserFactoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->passwordEncoderFactory->method('getEncoder')->willReturn(new BCryptPasswordEncoder(13));
        $this->validator->method('validate')->willReturn([]);
    }

    public function testItImplements()
    {
        $registerTypeHandler = new RegisterTypeHandler(
            $this->cloudStoragePersister,
            $this->passwordEncoderFactory,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->imageRetrieverHelper,
            $this->messageBus,
            $this->userFactory,
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
            $this->passwordEncoderFactory,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->imageRetrieverHelper,
            $this->messageBus,
            $this->userFactory,
            $this->userRepository,
            $this->validator
        );

        $formInterfaceMock->method('isValid')->willReturn(false);
        $formInterfaceMock->method('isSubmitted')->willReturn(false);

        static::assertFalse($registerTypeHandler->handle($formInterfaceMock));
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
            $this->passwordEncoderFactory,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->imageRetrieverHelper,
            $this->messageBus,
            $this->userFactory,
            $this->userRepository,
            $this->validator
        );

        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userRegistrationDTOMock);
        $fileTypeMock->method('getData')->willReturn(null);

        static::assertTrue($registerTypeHandler->handle($formInterfaceMock));
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
            $this->passwordEncoderFactory,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->imageRetrieverHelper,
            $this->messageBus,
            $this->userFactory,
            $this->userRepository,
            $this->validator
        );

        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userRegistrationDTOMock);

        static::assertTrue($registerTypeHandler->handle($formInterfaceMock));
    }
}
