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

namespace tests\FormHandler;

use App\Interactor\UserInteractor;
use Doctrine\ORM\EntityManagerInterface;
use App\FormHandler\RegisterTypeHandler;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Form\FormInterface;
use App\Builder\Interfaces\UserBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterTypeHandlerTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandlerTest extends KernelTestCase
{
    /**
     * @var Workflow
     */
    private $workflowRegistry;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->workflowRegistry = static::bootKernel()->getContainer()
                                                      ->get('workflow.user_status');

        $this->userPasswordEncoder = static::bootKernel()->getContainer()
                                                         ->get('security.password_encoder');
    }

    public function testItImplement()
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $userPasswordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $registerTypeHandler = new RegisterTypeHandler(
            $this->workflowRegistry,
            $entityManagerMock,
            $userPasswordEncoderMock
        );

        static::assertInstanceOf(
            RegisterTypeHandlerInterface::class,
            $registerTypeHandler
        );
    }

    public function testFormHandlingFailure()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $userInteractorMock = $this->createMock(UserInteractor::class);
        $userBuilderMock = $this->createMock(UserBuilderInterface::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $formInterfaceMock->method('isSubmitted')
                          ->willReturn(false);
        $formInterfaceMock->method('isValid')
                          ->willReturn(false);

        $userInteractorMock->method('getUsername')
                           ->willReturn('Toto');

        $userInteractorMock->method('getEmail')
                           ->willReturn('toto@gmail.com');

        $userInteractorMock->method('getPlainPassword')
                           ->willReturn('Ie1FDLTOTO');

        $userBuilderMock->method('getUser')
                        ->willReturn($userInteractorMock);

        $registerTypeHandler = new RegisterTypeHandler(
            $this->workflowRegistry,
            $entityManagerMock,
            $this->userPasswordEncoder
        );

        static::assertFalse(
            $registerTypeHandler->handle($formInterfaceMock, $userBuilderMock)
        );
    }

    public function testFormHandling()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $userInteractorMock = $this->createMock(UserInteractor::class);
        $userBuilderMock = $this->createMock(UserBuilderInterface::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $formInterfaceMock->method('isSubmitted')
                          ->willReturn(true);
        $formInterfaceMock->method('isValid')
                          ->willReturn(true);

        $userInteractorMock->method('getUsername')
                           ->willReturn('Toto');

        $userInteractorMock->method('getEmail')
                           ->willReturn('toto@gmail.com');

        $userInteractorMock->method('getPlainPassword')
                           ->willReturn('Ie1FDLTOTO');

        $userBuilderMock->method('getUser')
                        ->willReturn($userInteractorMock);

        $registerTypeHandler = new RegisterTypeHandler(
            $this->workflowRegistry,
            $entityManagerMock,
            $this->userPasswordEncoder
        );

        static::assertTrue(
            $registerTypeHandler->handle($formInterfaceMock, $userBuilderMock)
        );
    }
}
