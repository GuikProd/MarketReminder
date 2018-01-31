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

namespace App\Subscriber\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\TranslatorInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;

/**
 * Class ProfileImageSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProfileImageSubscriber implements ProfileImageSubscriberInterface
{
    const AVAILABLE_TYPES = ['image/jpeg', 'image/png'];

    /**
     * @var TranslatorInterface
     */
    private $translatorInterface;

    /**
     * ProfileImageSubscriber constructor.
     *
     * @param TranslatorInterface $translatorInterface
     */
    public function __construct(TranslatorInterface $translatorInterface)
    {
        $this->translatorInterface = $translatorInterface;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'onSubmit'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onSubmit(FormEvent $event): void
    {
        if ($event->getData() === null) {
            return;
        }

        if (!in_array($event->getData()->getMimeType(), self::AVAILABLE_TYPES)) {
            $event->getForm()->addError(
                new FormError(
                    $this->translatorInterface->trans(
                        'form.format_error', [], 'validators'
                    )
                )
            );
        }
    }
}
