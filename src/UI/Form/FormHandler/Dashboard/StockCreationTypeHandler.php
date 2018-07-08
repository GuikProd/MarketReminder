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

use App\Domain\Repository\Interfaces\StockRepositoryInterface;
use App\UI\Form\FormHandler\Dashboard\Interfaces\StockCreationTypeHandlerInterface;
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
     * @var Registry
     */
    private $workflowRegistry;

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
        Registry $workflowRegistry,
        StockRepositoryInterface $stockRepository,
        TokenStorageInterface $tokenStorage,
        ValidatorInterface $validator
    ) {
        $this->workflowRegistry = $workflowRegistry;
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

            return true;
        }

        return false;
    }
}
