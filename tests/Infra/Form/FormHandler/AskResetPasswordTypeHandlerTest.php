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

namespace App\Tests\Infra\Form\FormHandler;

use App\Domain\UseCase\UserResetPassword\DTO\UserResetPasswordDTO;
use App\Infra\Form\FormHandler\AskResetPasswordTypeHandler;
use App\Infra\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class AskResetPasswordTypeHandlerTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeHandlerTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()
                                              ->get('doctrine.orm.entity_manager');

        $this->eventDispatcher = static::$kernel->getContainer()
                                                ->get('event_dispatcher');
    }

    public function testWrongHandlingProcess()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $sessionInterface = $this->createMock(SessionInterface::class);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler(
                                               $sessionInterface,
                                               $this->entityManager,
                                               $this->eventDispatcher
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

        $sessionMock = new Session(new MockArraySessionStorage());

        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userPasswordResetDTOMock);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler(
                                               $sessionMock,
                                               $this->entityManager,
                                               $this->eventDispatcher
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

        $sessionMock = new Session(new MockArraySessionStorage());

        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $formInterfaceMock->method('getData')->willReturn($userPasswordResetDTOMock);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler(
                                               $sessionMock,
                                               $this->entityManager,
                                               $this->eventDispatcher
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
