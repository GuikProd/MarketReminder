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
use App\UI\Responder\Dashboard\Stock\Interfaces\StockListingResponderInterface;
use App\UI\Responder\Dashboard\Stock\StockListingResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class StockListingResponderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockListingResponderUnitTest extends TestCase
{
    /**
     * @var Environment|null
     */
    private $twig = null;

    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->presenter = $this->createMock(PresenterInterface::class);
    }

    public function testItImplements()
    {
        $responder = new StockListingResponder($this->twig, $this->presenter);

        static::assertInstanceOf(StockListingResponderInterface::class, $responder);
    }

    public function testItReturn()
    {
        $requestMock = $this->createMock(Request::class);

        $responder = new StockListingResponder($this->twig, $this->presenter);

        static::assertInstanceOf(Response::class, $responder($requestMock));
    }
}
