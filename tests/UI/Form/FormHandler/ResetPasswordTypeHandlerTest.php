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
use App\UI\Form\FormHandler\Interfaces\ResetPasswordTypeHandlerInterface;
use App\UI\Form\FormHandler\ResetPasswordTypeHandler;
use App\UI\Presenter\Security\Interfaces\ResetPasswordPresenterInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class ResetPasswordTypeHandlerTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordTypeHandlerTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var FormInterface
     */
    private $formInterface;

    /**
     * @var ResetPasswordPresenterInterface
     */
    private $resetPasswordPresenter;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->formInterface = $this->createMock(FormInterface::class);
        $this->resetPasswordPresenter = $this->createMock(ResetPasswordPresenterInterface::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
    }

    public function testItImplements()
    {
        $resetPasswordTypeHandler = new ResetPasswordTypeHandler(
            $this->eventDispatcher,
            $this->encoderFactory,
            $this->resetPasswordPresenter,
            $this->userRepository
        );

        static::assertInstanceOf(
            ResetPasswordTypeHandlerInterface::class,
            $resetPasswordTypeHandler
        );
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     */
    public function testBlackfireProfilingWithWrongDataAreSubmitted()
    {
        $resetPasswordTypeHandler = new ResetPasswordTypeHandler(
            $this->eventDispatcher,
            $this->encoderFactory,
            $this->resetPasswordPresenter,
            $this->userRepository
        );

        $probe = static::$blackfire->createProbe();

        $resetPasswordTypeHandler->handle($this->formInterface, $this->user);

        static::$blackfire->endProbe($probe);
    }

    public function testWrongDataAreSubmitted()
    {
        $this->formInterface->method('isSubmitted')->willReturn(false);
        $this->formInterface->method('isValid')->willReturn(false);

        $resetPasswordTypeHandler = new ResetPasswordTypeHandler(
            $this->eventDispatcher,
            $this->encoderFactory,
            $this->resetPasswordPresenter,
            $this->userRepository
        );

        $resetPasswordTypeHandler->handle($this->formInterface, $this->user);
    }
}
