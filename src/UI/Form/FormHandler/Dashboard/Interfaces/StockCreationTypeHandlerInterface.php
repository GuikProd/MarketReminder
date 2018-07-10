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

namespace App\UI\Form\FormHandler\Dashboard\Interfaces;

use App\Domain\Builder\Interfaces\StockBuilderInterface;
use App\Domain\Builder\Interfaces\StockItemsBuilderInterface;
use App\Domain\Repository\Interfaces\StockRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Registry;

/**
 * Interface StockCreationTypeHandlerInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockCreationTypeHandlerInterface
{
    /**
     * StockCreationTypeHandlerInterface constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param Registry $workflowRegistry
     * @param StockBuilderInterface $stockBuilder
     * @param StockItemsBuilderInterface $stockItemsBuilder
     * @param StockRepositoryInterface $stockRepository
     * @param TokenStorageInterface $tokenStorage
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        Registry $workflowRegistry,
        StockBuilderInterface $stockBuilder,
        StockItemsBuilderInterface $stockItemsBuilder,
        StockRepositoryInterface $stockRepository,
        TokenStorageInterface $tokenStorage,
        ValidatorInterface $validator
    );

    /**
     * @param FormInterface $form
     *
     * @return bool
     */
    public function handle(FormInterface $form): bool;
}
