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
use App\Domain\Builder\Interfaces\StockBuilderInterface;
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
     * @var StockBuilderInterface
     */
    private $stockBuilder;

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
        StockBuilderInterface $stockBuilder,
        StockRepositoryInterface $stockRepository,
        TokenStorageInterface $tokenStorage,
        ValidatorInterface $validator
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->workflowRegistry = $workflowRegistry;
        $this->stockBuilder = $stockBuilder;
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

            $this->stockBuilder->createFromUI($form->getData(), $this->tokenStorage->getToken()->getUser());

            $errors = $this->validator->validate($this->stockBuilder->getStock(), [], 'stock_creation');

            if (\count($errors) > 0) {

                $this->eventDispatcher->dispatch(
                    SessionMessageEvent::NAME,
                    new SessionMessageEvent('failure', 'stock.creation_failure')
                );

                return false;
            }

            $workflow = $this->workflowRegistry->get($this->stockBuilder->getStock());
            $workflow->apply($this->stockBuilder->getStock(), 'used');

            $this->stockRepository->save($this->stockBuilder->getStock());

            return true;
        }

        return false;
    }
}
