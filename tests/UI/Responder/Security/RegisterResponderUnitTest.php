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

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Security\Interfaces\RegisterResponderInterface;
use App\UI\Responder\Security\RegisterResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class RegisterResponderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RegisterResponderUnitTest extends TestCase
{
    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * @var Environment|null
     */
    private $twig = null;

    /**
     * @var UrlGeneratorInterface|null
     */
    private $urlGenerator = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->presenter = $this->createMock(PresenterInterface::class);
        $this->request = Request::create('/fr/register', 'GET');
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            RegisterResponderInterface::class,
            $registerResponder
        );
    }

    public function testRedirectResponseIsReturned()
    {
        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $registerResponder($this->request, true)
        );
    }

    public function testResponseIsReturned()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $registerResponder = new RegisterResponder(
            $this->twig,
            $this->presenter,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            Response::class,
            $registerResponder($this->request, false, $formInterfaceMock)
        );
    }
}
