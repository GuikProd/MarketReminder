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

namespace App\Tests\Infra\Form\FormHandler;

use App\Domain\UseCase\UserRegistration\DTO\UserRegistrationDTO;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use App\FormHandler\RegisterTypeHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterTypeHandlerTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandlerTest extends KernelTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->validator = static::bootKernel()->getContainer()
                                               ->get('validator');

        $this->eventDispatcher = static::bootKernel()->getContainer()
                                                     ->get('event_dispatcher');

        $this->encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->encoderFactory->method('getEncoder')->willReturn(new BCryptPasswordEncoder(13));
    }

    public function testItImplementsRegisterTypeHandlerInterface()
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $registerTypeHandler = new RegisterTypeHandler(
            $this->validator,
            $entityManagerMock,
            $this->eventDispatcher,
            $this->encoderFactory
        );

        static::assertInstanceOf(
            RegisterTypeHandlerInterface::class,
            $registerTypeHandler
        );
    }

    public function testWrongHandlingProcess()
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $registerTypeHandler = new RegisterTypeHandler(
            $this->validator,
            $entityManagerMock,
            $this->eventDispatcher,
            $this->encoderFactory
        );

        $formInterfaceMock->method('isValid')->willReturn(false);

        static::assertFalse(
            $registerTypeHandler->handle($formInterfaceMock)
        );
    }

    public function testRightHandlingProcess()
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $userRegistrationDTOMock = new UserRegistrationDTO(
            'Toto',
            'toto@gmail.com',
            'Ie1FDLTOTO',
            'da248z614d2az68d'
        );

        $registerTypeHandler = new RegisterTypeHandler(
            $this->validator,
            $entityManagerMock,
            $this->eventDispatcher,
            $this->encoderFactory
        );

        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userRegistrationDTOMock);

        static::assertTrue(
            $registerTypeHandler->handle($formInterfaceMock)
        );
    }
}
