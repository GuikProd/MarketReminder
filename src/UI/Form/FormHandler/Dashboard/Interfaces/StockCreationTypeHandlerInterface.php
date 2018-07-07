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

use App\Domain\Repository\Interfaces\StockRepositoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @param StockRepositoryInterface $stockRepository
     * @param TokenStorageInterface $tokenStorage
     * @param ValidatorInterface $validator
     */
    public function __construct(
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
