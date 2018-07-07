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

namespace App\UI\Form\Subscriber;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Form\Subscriber\Interfaces\StockItemCreationSubscriberInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class StockItemCreationSubscriber.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemCreationSubscriber implements StockItemCreationSubscriberInterface, EventSubscriberInterface
{
    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudTranslationRepositoryInterface $cloudTranslationRepository,
        RequestStack $requestStack
    ) {
        $this->cloudTranslationRepository = $cloudTranslationRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onPreSubmit(FormEvent $event): void
    {
        $currentLocale = $this->requestStack->getCurrentRequest()->getLocale();

        try {
            $cacheKey = $this->cloudTranslationRepository->getSingleEntry(
                'form.'.$currentLocale.'.yaml',
                $currentLocale,
                'stock.creation.item_type'
            );

            if (ucfirst($event->getData()) !== $cacheKey->getValue()) {
                return;
            }

            $event->getForm()->getParent()->add('limitUsageDate', DateType::class);
            $event->getForm()->getParent()->add('limitConsumptionDate', DateType::class);
            $event->getForm()->getParent()->add('limitOptimalUsageDate', DateType::class);
        } catch (InvalidArgumentException $exception) {
            $event->getForm()->addError(
                new FormError(
                    $exception->getMessage()
                )
            );
        }
    }
}
