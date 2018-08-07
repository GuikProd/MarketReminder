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

namespace App\Tests\UI\Form\FormHandler\Dashboard;

use App\Domain\Builder\Interfaces\StockFactoryInterface;
use App\Domain\Builder\Interfaces\StockItemsBuilderInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\StockRepositoryInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockCreationDTO;
use App\UI\Form\FormHandler\Dashboard\Interfaces\StockCreationTypeHandlerInterface;
use App\UI\Form\FormHandler\Dashboard\StockCreationTypeHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

/**
 * Class StockCreationTypeHandlerUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationTypeHandlerUnitTest extends TestCase
{
    /**
     * @var EventDispatcherInterface|null
     */
    private $eventDispatcher = null;

    /**
     * @var FormInterface
     */
    private $formInterface = null;

    /**
     * @var Registry|null
     */
    private $workflowRegistry = null;

    /**
     * @var StockFactoryInterface|null
     */
    private $stockBuilder = null;

    /**
     * @var StockItemsBuilderInterface|null
     */
    private $stockItemsBuilder = null;

    /**
     * @var StockRepositoryInterface|null
     */
    private $stockRepository = null;

    /**
     * @var TokenStorageInterface|null
     */
    private $tokenStorage = null;

    /**
     * @var ValidatorInterface
     */
    private $validator = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->formInterface = $this->createMock(FormInterface::class);
        $this->workflowRegistry = $this->createMock(Registry::class);
        $this->stockBuilder = $this->createMock(StockFactoryInterface::class);
        $this->stockItemsBuilder = $this->createMock(StockItemsBuilderInterface::class);
        $this->stockRepository = $this->createMock(StockRepositoryInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testItImplements()
    {
        $handler = new StockCreationTypeHandler(
            $this->eventDispatcher,
            $this->workflowRegistry,
            $this->stockBuilder,
            $this->stockItemsBuilder,
            $this->stockRepository,
            $this->tokenStorage,
            $this->validator
        );

        static::assertInstanceOf(
            StockCreationTypeHandlerInterface::class,
            $handler
        );
    }

    public function testItRefuseToHandle()
    {
        $handler = new StockCreationTypeHandler(
            $this->eventDispatcher,
            $this->workflowRegistry,
            $this->stockBuilder,
            $this->stockItemsBuilder,
            $this->stockRepository,
            $this->tokenStorage,
            $this->validator
        );

        $this->formInterface->method('isSubmitted')->willReturn(false);
        $this->formInterface->method('isValid')->willReturn(false);

        static::assertFalse($handler->handle($this->formInterface));
    }

    public function testItHandle()
    {
        $tokenMock = $this->createMock(TokenInterface::class);
        $userMock = $this->createMock(UserInterface::class);
        $dtoMock = new StockCreationDTO('', '', [], []);
        $workflowMock = $this->createMock(Workflow::class);

        $this->formInterface->method('getData')->willReturn($dtoMock);
        $this->tokenStorage->method('getToken')->willReturn($tokenMock);
        $tokenMock->method('getUser')->willReturn($userMock);
        $this->validator->method('validate')->willReturn([]);
        $this->workflowRegistry->method('get')->willReturn($workflowMock);

        $handler = new StockCreationTypeHandler(
            $this->eventDispatcher,
            $this->workflowRegistry,
            $this->stockBuilder,
            $this->stockItemsBuilder,
            $this->stockRepository,
            $this->tokenStorage,
            $this->validator
        );

        $this->formInterface->method('isSubmitted')->willReturn(true);
        $this->formInterface->method('isValid')->willReturn(true);

        static::assertTrue($handler->handle($this->formInterface));
    }
}
