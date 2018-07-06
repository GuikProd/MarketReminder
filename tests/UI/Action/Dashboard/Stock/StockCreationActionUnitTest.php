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

use App\UI\Action\Dashboard\Stock\Interfaces\StockCreationActionInterface;
use App\UI\Action\Dashboard\Stock\StockCreationAction;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockCreationResponderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StockCreationActionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationActionUnitTest extends TestCase
{
    /**
     * @var null|Request
     */
    private $request = null;

    /**
     * @var StockCreationResponderInterface
     */
    private $responder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->responder = $this->createMock(StockCreationResponderInterface::class);

        $this->request = Request::create('/fr/dashboard/stock/creation', 'GET');
    }

    public function testItImplements()
    {
        $action = new StockCreationAction();

        static::assertInstanceOf(
            StockCreationActionInterface::class,
            $action
        );
    }

    public function testItReturn()
    {
        $action = new StockCreationAction();

        static::assertInstanceOf(
            Response::class,
            $action($this->request, $this->responder)
        );
    }
}
