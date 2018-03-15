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

use App\UI\Action\Security\ValidationTokenAction;
use App\Domain\Models\Interfaces\UserInterface;
use App\Responder\Security\ValidationTokenResponder;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class ValidationTokenActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ValidationTokenActionTest extends TestCase
{
    /**
     * Allow to test the validation of an account using a "mock" of the Request and Session.
     */
    public function testReturn()
    {
        $session = new Session(new MockArraySessionStorage());
        $translator = $this->createMock(TranslatorInterface::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $userMock = $this->createMock(UserInterface::class);

        $requestMock = new Request([], [], ['token' => 'ToFEGARRdjLs2', 'user' => $userMock]);

        $validationTokenAction = new ValidationTokenAction(
            $session,
            $translator,
            $entityManagerMock,
            $eventDispatcherMock
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
