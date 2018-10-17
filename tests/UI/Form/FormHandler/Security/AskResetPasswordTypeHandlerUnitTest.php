<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\UI\Form\FormHandler\Security;

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\UseCase\UserResetPassword\DTO\UserResetPasswordDTO;
use App\UI\Form\FormHandler\Security\AskResetPasswordTypeHandler;
use App\UI\Form\FormHandler\Security\Interfaces\AskResetPasswordTypeHandlerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AskResetPasswordTypeHandlerUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class AskResetPasswordTypeHandlerUnitTest extends TestCase
{
    /**
     * @var MessageBusInterface|null
     */
    private $messageBus = null;

    /**
     * @var UserRepositoryInterface|null
     */
    private $userRepository = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
    }

    public function testWrongHandlingProcess()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler(
            $this->messageBus,
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
        $formInterfaceMock->method('isValid')->willReturn(false);
        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userPasswordResetDTOMock);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler(
            $this->messageBus,
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
            $this->messageBus,
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
