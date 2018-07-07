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

namespace App\UI\Form\Subscriber\Interfaces;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Interface StockItemCreationSubscriberInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockItemCreationSubscriberInterface
{
    /**
     * StockItemCreationSubscriberInterface constructor.
     *
     * @param CloudTranslationRepositoryInterface $cloudTranslationRepository
     * @param RequestStack $requestStack
     */
    public function __construct(
        CloudTranslationRepositoryInterface $cloudTranslationRepository,
        RequestStack $requestStack
    );

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event): void;
}
