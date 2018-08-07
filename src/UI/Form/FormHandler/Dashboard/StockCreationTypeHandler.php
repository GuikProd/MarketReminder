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

namespace App\UI\Form\FormHandler\Dashboard;

use App\Application\Event\SessionMessageEvent;
use App\Domain\Factory\Interfaces\StockFactoryInterface;
use App\Domain\Builder\Interfaces\StockItemsBuilderInterface;
use App\Domain\Repository\Interfaces\StockRepositoryInterface;
use App\UI\Form\FormHandler\Dashboard\Interfaces\StockCreationTypeHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Registry;

/**
 * Class StockCreationTypeHandler.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationTypeHandler implements StockCreationTypeHandlerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var Registry
     */
    private $workflowRegistry;

    /**
     * @var StockFactoryInterface
     */
    private $stockBuilder;

    /**
     * @var StockItemsBuilderInterface
     */
    private $stockItemsBuilder;

    /**
     * @var StockRepositoryInterface
     */
    private $stockRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        Registry $workflowRegistry,
        StockFactoryInterface $stockBuilder,
        StockItemsBuilderInterface $stockItemsBuilder,
        StockRepositoryInterface $stockRepository,
        TokenStorageInterface $tokenStorage,
        ValidatorInterface $validator
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->workflowRegistry = $workflowRegistry;
        $this->stockBuilder = $stockBuilder;
        $this->stockItemsBuilder = $stockItemsBuilder;
        $this->stockRepository = $stockRepository;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {

            $form->getData()->stockItems
                ? $this->stockItemsBuilder->createFromUI($form->getData()->stockItems)
                : $form->getData()->stockItems = [];

            $stock = $this->stockBuilder->createFromUI(
                $form->getData(),
                $this->tokenStorage->getToken()->getUser(),
                $this->stockItemsBuilder->getStockItems()
            );

            $errors = $this->validator->validate($stock, [], 'stock_creation');

            if (\count($errors) > 0) {

                $this->eventDispatcher->dispatch(
                    SessionMessageEvent::NAME,
                    new SessionMessageEvent('failure', 'stock.creation_failure')
                );

                return false;
            }

            $workflow = $this->workflowRegistry->get($stock);
            $workflow->apply($stock, 'used');

            $this->stockRepository->save($stock);

            return true;
        }

        return false;
    }
}
