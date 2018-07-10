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
use App\UI\Form\FormHandler\Dashboard\Interfaces\StockCreationTypeHandlerInterface;
use App\UI\Responder\Dashboard\Stock\Interfaces\StockCreationResponderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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
     * @var FormFactoryInterface|null
     */
    private $formFactory = null;

    /**
     * @var null|Request
     */
    private $request = null;

    /**
     * @var StockCreationResponderInterface
     */
    private $responder;

    /**
     * @var StockCreationTypeHandlerInterface|null
     */
    private $stockCreationTypeHandler = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->responder = $this->createMock(StockCreationResponderInterface::class);
        $this->stockCreationTypeHandler = $this->createMock(StockCreationTypeHandlerInterface::class);

        $this->request = Request::create('/fr/dashboard/stock/creation', 'GET');
    }

    public function testItImplements()
    {
        $action = new StockCreationAction(
            $this->formFactory,
            $this->stockCreationTypeHandler
        );

        static::assertInstanceOf(
            StockCreationActionInterface::class,
            $action
        );
    }

    public function testItReturn()
    {
        $formMock = $this->createMock(FormInterface::class);

        $this->formFactory->method('create')->willReturn($formMock);
        $formMock->method('handleRequest')->willReturnSelf();

        $action = new StockCreationAction(
            $this->formFactory,
            $this->stockCreationTypeHandler
        );

        static::assertInstanceOf(
            Response::class,
            $action($this->request, $this->responder)
        );
    }
}
