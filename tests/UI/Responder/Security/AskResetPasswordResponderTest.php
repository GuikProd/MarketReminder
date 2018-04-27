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

use App\UI\Presenter\Security\AskResetPasswordPresenter;
use App\UI\Presenter\Security\Interfaces\AskResetPasswordPresenterInterface;
use App\UI\Responder\Security\AskResetPasswordResponder;
use App\UI\Responder\Security\Interfaces\AskResetPasswordResponderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
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
    /**
     * @var AskResetPasswordPresenterInterface
     */
    private $askResetPasswordPresenter;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->askResetPasswordPresenter = $this->createMock(AskResetPasswordPresenterInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $askResetPasswordResponder = new AskResetPasswordResponder(
            $this->askResetPasswordPresenter,
            $this->twig,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            AskResetPasswordResponderInterface::class,
            $askResetPasswordResponder
        );
    }

    /**
     * @group Blackfire
     */
    public function testBlackfireProfilingResponseIsReturned()
    {
        $askResetPasswordPresenter = new AskResetPasswordPresenter();

        $formInterface = $this->createMock(FormInterface::class);

        $askResetPasswordResponder = new AskResetPasswordResponder(
            $askResetPasswordPresenter,
            $this->twig,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            Response::class,
            $askResetPasswordResponder(false, $formInterface)
        );
    }

    public function testResponseIsReturned()
    {
        $formInterface = $this->createMock(FormInterface::class);

        $askResetPasswordResponder = new AskResetPasswordResponder(
            $this->askResetPasswordPresenter,
            $this->twig,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            Response::class,
            $askResetPasswordResponder(false, $formInterface)
        );
    }

    public function testRedirectResponseIsReturned()
    {
        $askResetPasswordResponder = new AskResetPasswordResponder(
            $this->askResetPasswordPresenter,
            $this->twig,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $askResetPasswordResponder(true)
        );
    }
}
