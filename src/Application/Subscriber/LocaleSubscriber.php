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

namespace App\Application\Subscriber;

use App\Application\Subscriber\Interfaces\LocaleSubscriberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class LocaleSubscriber
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class LocaleSubscriber implements EventSubscriberInterface, LocaleSubscriberInterface
{
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onLocaleChange', 20]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onLocaleChange(GetResponseEvent $event): void
    {
        if (!$event->getRequest()->hasPreviousSession()) {
            return;
        }

        if ($locale = $event->getRequest()->attributes->get('_locale')) {
            $event->getRequest()->getSession()->set('_locale', $locale);
        } else {
            $event->getRequest()->setLocale(
                $event->getRequest()->getSession()->get('_locale', $this->defaultLocale)
            );
        }
    }
}
