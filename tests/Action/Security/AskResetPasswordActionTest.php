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

namespace tests\Action\Security;

use App\Action\Security\AskResetPasswordAction;
use App\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use App\Responder\Security\AskResetPasswordResponder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AskResetPasswordActionTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordActionTest extends TestCase
{
    public function testInvokeReturn()
    {
        $sessionMock = new Session(new MockArraySessionStorage());
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $askResetPasswordTypeHandlerMock = $this->createMock(AskResetPasswordTypeHandlerInterface::class);

        $urlGeneratorMock->method('generate')
                         ->willReturn('/fr/');

        $askResetPasswordAction = new AskResetPasswordAction(
            $sessionMock,
            $formFactoryMock,
            $entityManagerMock,
            $askResetPasswordTypeHandlerMock
        );

        $askResetPasswordResponder = new AskResetPasswordResponder($urlGeneratorMock);

        static::assertInstanceOf(
            RedirectResponse::class,
            $askResetPasswordAction($askResetPasswordResponder)
        );
    }
}
