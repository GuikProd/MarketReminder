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

    /**
     * Allow to store the image locally, analyse it then if everything's right,
     * upload the file inside the Google Cloud Bucket.
     *
     * @param FormEvent $event    @see FormEvent::POST_SUBMIT
     *
     * @return bool               If the process goes right or wrong.
     */
    public function uploadAndAnalyseImage(FormEvent $event): bool;
}
