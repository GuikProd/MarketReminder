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

namespace App\Subscriber\Interfaces;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Interface ProfileImageSubscriberInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ProfileImageSubscriberInterface extends EventSubscriberInterface
{
    /**
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event): void;
}
