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

namespace App\Tests\UI\Responder\Dashboard\Stock;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockCreationResponderInterface;
use App\UI\Responder\Dashboard\Stock\StockCreationResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class StockCreationResponderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationResponderUnitTest extends TestCase
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
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->request = Request::create('/fr/dashboard/stock/creation', 'GET');
        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $responder = new StockCreationResponder(
            $this->presenter,
            $this->twig,
            $this->urlGenerator
        );

        static::assertInstanceOf(StockCreationResponderInterface::class, $responder);
    }

    public function testItReturnAResponse()
    {
        $formMock = $this->createMock(FormInterface::class);

        $responder = new StockCreationResponder(
            $this->presenter,
            $this->twig,
            $this->urlGenerator
        );

        static::assertInstanceOf(Response::class, $responder($this->request, $formMock));
    }

    public function testItReturnARedirectResponse()
    {
        $responder = new StockCreationResponder(
            $this->presenter,
            $this->twig,
            $this->urlGenerator
        );

        static::assertInstanceOf(
            Response::class,
            $responder($this->request, null, true)
        );
    }
}
