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

use App\UI\Responder\Security\AskResetPasswordResponder;
use App\UI\Responder\Security\Interfaces\AskResetPasswordResponderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class AskResetPasswordResponderTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordResponderTest extends TestCase
{
    public function testItImplements()
    {
        $twigMock = $this->createMock(Environment::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $urlGeneratorMock->method('generate')->willReturn('/fr/');

        $askResetPasswordResponder = new AskResetPasswordResponder($twigMock, $urlGeneratorMock);

        static::assertInstanceOf(
            AskResetPasswordResponderInterface::class,
            $askResetPasswordResponder
        );
    }

    public function testResponseIsReturned()
    {
        $formViewMock = $this->createMock(FormView::class);
        $twigMock = $this->createMock(Environment::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $urlGeneratorMock->method('generate')->willReturn('/fr/');

        $askResetPasswordResponder = new AskResetPasswordResponder($twigMock, $urlGeneratorMock);

        static::assertInstanceOf(
            Response::class,
            $askResetPasswordResponder($formViewMock)
        );
    }

    public function testRedirectResponseIsReturned()
    {
        $twigMock = $this->createMock(Environment::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $urlGeneratorMock->method('generate')->willReturn('/fr/');

        $askResetPasswordResponder = new AskResetPasswordResponder($twigMock, $urlGeneratorMock);

        static::assertInstanceOf(
            RedirectResponse::class,
            $askResetPasswordResponder(
                null,
                true,
                'index',
                '')
        );
    }
}
