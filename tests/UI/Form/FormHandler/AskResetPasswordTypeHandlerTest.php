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

namespace App\Tests\UI\Form\FormHandler;

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\UseCase\UserResetPassword\DTO\UserResetPasswordDTO;
use App\UI\Form\FormHandler\AskResetPasswordTypeHandler;
use App\UI\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class AskResetPasswordTypeHandlerTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeHandlerTest extends KernelTestCase
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->eventDispatcher = static::$kernel->getContainer()->get('event_dispatcher');
        $this->session = new Session(new MockArraySessionStorage());
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
    }

    public function testWrongHandlingProcess()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler(
            $this->eventDispatcher,
            $this->session,
            $this->userRepository
        );

        static::assertInstanceOf(
            AskResetPasswordTypeHandlerInterface::class,
            $askResetPasswordTypeHandler
        );

        static::assertFalse(
            $askResetPasswordTypeHandler->handle($formInterfaceMock)
        );
    }

    public function testRightHandlingProcessWithWrongUser()
    {
        $userPasswordResetDTOMock = new UserResetPasswordDTO('tutu@gmail.com', 'Tutu');

        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userPasswordResetDTOMock);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler(
            $this->eventDispatcher,
            $this->session,
            $this->userRepository
        );

        static::assertInstanceOf(
            AskResetPasswordTypeHandlerInterface::class,
            $askResetPasswordTypeHandler
        );

        static::assertFalse(
            $askResetPasswordTypeHandler->handle($formInterfaceMock)
        );
    }

    public function testRightHandlingProcessWithRightUser()
    {
        $userPasswordResetDTOMock = new UserResetPasswordDTO('hp@gmail.com', 'HP');
        $userMock = $this->createMock(UserInterface::class);
        $userMock->method('getUsername')->willReturn('HP');
        $userMock->method('getEmail')->willReturn('hp@gmail.com');

        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userPasswordResetDTOMock);
        $this->userRepository->method('getUserByUsernameAndEmail')->willReturn($userMock);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler(
            $this->eventDispatcher,
            $this->session,
            $this->userRepository
        );

        static::assertInstanceOf(
            AskResetPasswordTypeHandlerInterface::class,
            $askResetPasswordTypeHandler
        );

        static::assertTrue(
            $askResetPasswordTypeHandler->handle($formInterfaceMock)
        );
    }
}
