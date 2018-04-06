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

namespace App\Tests\UI\Action\Security;

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Security\Interfaces\ValidationTokenActionInterface;
use App\UI\Action\Security\ValidationTokenAction;
use App\UI\Responder\Security\ValidationTokenResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ValidationTokenActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ValidationTokenActionTest extends TestCase
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
    }

    public function testItImplements()
    {
        $validationTokenAction = new ValidationTokenAction(
            $this->eventDispatcher,
            $this->userRepository
        );

        static::assertInstanceOf(
            ValidationTokenActionInterface::class,
            $validationTokenAction
        );
    }

    /**
     * Allow to test the validation of an account using a "mock" of the Request and Session.
     */
    public function testItReturn()
    {
        $userMock = $this->createMock(UserInterface::class);

        $requestMock = new Request([], [], ['token' => 'ToFEGARRdjLs2', 'user' => $userMock]);

        $validationTokenAction = new ValidationTokenAction(
            $this->eventDispatcher,
            $this->userRepository
        );

        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $urlGeneratorMock->method('generate')
                         ->willReturn('/fr/');

        $validationTokenResponder = new ValidationTokenResponder($urlGeneratorMock);

        static::assertInstanceOf(
            RedirectResponse::class,
            $validationTokenAction($requestMock, $validationTokenResponder)
        );
    }
}
