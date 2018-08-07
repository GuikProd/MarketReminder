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

namespace App\Tests\UI\Action\Dashboard\Stock;

use App\Domain\Repository\Interfaces\StockRepositoryInterface;
use App\UI\Action\Dashboard\Stock\Interfaces\StockListingActionInterface;
use App\UI\Action\Dashboard\Stock\StockListingAction;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockListingResponderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StockListingActionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockListingActionUnitTest extends TestCase
{
    /**
     * @var StockListingResponderInterface|null
     */
    private $stockListingResponder = null;

    /**
     * @var StockRepositoryInterface|null
     */
    private $stockRepository = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->stockListingResponder = $this->createMock(StockListingResponderInterface::class);
        $this->stockRepository = $this->createMock(StockRepositoryInterface::class);
    }

    public function testItImplements()
    {
        $action = new StockListingAction($this->stockRepository);

        static::assertInstanceOf(StockListingActionInterface::class, $action);
    }

    public function testItReturn()
    {
        $requestMock = $this->createMock(Request::class);
        $responseMock = $this->createMock(Response::class);

        $this->stockListingResponder->method('__invoke')->willReturn($responseMock);
        $this->stockRepository->method('getAllTricks')->willReturn([]);

        $action = new StockListingAction($this->stockRepository);

        static::assertInstanceOf(Response::class, $action($requestMock, $this->stockListingResponder));
    }
}
