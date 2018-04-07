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

namespace App\Application\Subscriber\Interfaces;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Interface LocaleSubscriberInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface LocaleSubscriberInterface
{
    /**
     * LocaleSubscriberInterface constructor.
     *
     * @param string  $defaultLocale
     */
    public function __construct(string $defaultLocale);

    /**
     * @param GetResponseEvent  $event
     */
    public function onLocaleChange(GetResponseEvent $event): void;
}
