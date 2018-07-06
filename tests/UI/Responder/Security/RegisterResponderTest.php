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

namespace App\Tests\UI\Responder\Security;

use App\UI\Responder\Security\Interfaces\RegisterResponderInterface;
use App\UI\Responder\Security\RegisterResponder;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class RegisterResponderTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class RegisterResponderTest extends TestCase
{
    use TestCaseTrait;

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
    public function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            RegisterResponderInterface::class,
            $registerResponder
        );
    }

    /**
     * @group Blackfire
     */
    public function testBlackfireProfilingWithRedirectResponse()
    {
        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->urlGenerator
        );

        $probe = static::$blackfire->createProbe();

        $registerResponder(true);

        static::$blackfire->endProbe($probe);

        static::assertInstanceOf(
            RedirectResponse::class,
            $registerResponder(true)
        );
    }

    /**
     * @group Blackfire
     */
    public function testBlackfireProfilingWithResponse()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->urlGenerator
        );

        $probe = static::$blackfire->createProbe();

        $registerResponder(false, $formInterfaceMock);

        static::$blackfire->endProbe($probe);

        static::assertInstanceOf(
            Response::class,
            $registerResponder(false, $formInterfaceMock)
        );
    }

    public function testRedirectResponseIsReturned()
    {
        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->urlGenerator
        );

        $registerResponder(true);

        static::assertInstanceOf(
            RedirectResponse::class,
            $registerResponder(true)
        );
    }

    public function testResponseIsReturned()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->urlGenerator
        );

        $registerResponder(false, $formInterfaceMock);

        static::assertInstanceOf(
            Response::class,
            $registerResponder(false, $formInterfaceMock)
        );
    }
}
