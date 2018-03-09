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

namespace App\Infra\Form\FormSubscriber\Interfaces;

use Symfony\Component\Form\FormEvent;

/**
 * Interface AskResetPasswordTypeSubscriberInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface AskResetPasswordTypeSubscriberInterface
{
    /**
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event): void;
}
