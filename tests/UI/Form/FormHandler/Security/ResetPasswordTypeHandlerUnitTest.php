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
use App\Domain\UseCase\UserRegistration\DTO\UserRegistrationDTO;
use App\UI\Form\FormHandler\Security\Interfaces\ResetPasswordTypeHandlerInterface;
use App\UI\Form\FormHandler\Security\ResetPasswordTypeHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class ResetPasswordTypeHandlerUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ResetPasswordTypeHandlerUnitTest extends TestCase
{
    /**
     * @var EncoderFactoryInterface|null
     */
    private $encoderFactory = null;

    /**
     * @var FormInterface|null
     */
    private $formInterface = null;

    /**
     * @var MessageBusInterface|null
     */
    private $messageBus = null;

    /**
     * @var UserInterface|null
     */
    private $user = null;

    /**
     * @var UserRepositoryInterface|null
     */
    private $userRepository = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->formInterface = $this->createMock(FormInterface::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->encoderFactory->method('getEncoder')->willReturn(new BCryptPasswordEncoder(13));

        $this->formInterface->method('getData')
                            ->willReturn(new UserRegistrationDTO(
                                'toto',
                                'toto@gmail.com',
                                'Ie1FDLTOTO',
                                'dzazddzaazdda'
                            ));
    }

    public function testItImplements()
    {
        $resetPasswordTypeHandler = new ResetPasswordTypeHandler(
            $this->encoderFactory,
            $this->messageBus,
            $this->userRepository
        );

        static::assertInstanceOf(
            ResetPasswordTypeHandlerInterface::class,
            $resetPasswordTypeHandler
        );
    }

    public function testWrongDataAreSubmitted()
    {
        $this->formInterface->method('isSubmitted')->willReturn(false);
        $this->formInterface->method('isValid')->willReturn(false);

        $resetPasswordTypeHandler = new ResetPasswordTypeHandler(
            $this->encoderFactory,
            $this->messageBus,
            $this->userRepository
        );

        static::assertFalse(
            $resetPasswordTypeHandler->handle($this->formInterface, $this->user)
        );
    }

    public function testGoodDataAreSubmitted()
    {
        $this->formInterface->method('isSubmitted')->willReturn(true);
        $this->formInterface->method('isValid')->willReturn(true);

        $resetPasswordTypeHandler = new ResetPasswordTypeHandler(
            $this->encoderFactory,
            $this->messageBus,
            $this->userRepository
        );

        static::assertTrue(
            $resetPasswordTypeHandler->handle($this->formInterface, $this->user)
        );
    }
}
