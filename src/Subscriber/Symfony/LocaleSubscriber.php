<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Subscriber\Symfony;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LocaleSubscriber
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * LocaleSubscriber constructor.
     *
     * @param string $defaultLocale
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
     * Allow to set the locale depending on the one passed through the session.
     *
     * @param GetResponseEvent $event
     */
    public function onLocaleChange(GetResponseEvent $event)
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
