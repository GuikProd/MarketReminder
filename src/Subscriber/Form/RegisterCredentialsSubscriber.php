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

use App\Interactor\UserInteractor;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Subscriber\Interfaces\RegisterCredentialsSubscriberInterface;

/**
 * Class RegisterCredentialsSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterCredentialsSubscriber implements RegisterCredentialsSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translatorInterface;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * RegisterCredentialsSubscriber constructor.
     *
     * @param TranslatorInterface $translatorInterface
     * @param EntityManagerInterface $entityManagerInterface
     */
    public function __construct(
        TranslatorInterface $translatorInterface,
        EntityManagerInterface $entityManagerInterface
    ) {
        $this->translatorInterface = $translatorInterface;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'checkCredentials'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials(FormEvent $event): void
    {
        if (!$event->getData() || null === $event->getData()) {
            return;
        }

        $email = $this->entityManagerInterface
                      ->getRepository(UserInteractor::class)
                      ->getUserByEmail($event->getData()->getEmail());

        $username = $this->entityManagerInterface
                         ->getRepository(UserInteractor::class)
                         ->getUserByUsername($event->getData()->getUsername());

        if ($username || $email) {
            $event->getForm()->addError(
                new FormError(
                    $this->translatorInterface
                         ->trans(
                             'form.credentials_error', [], 'validators'
                         )
                )
            );
        }
    }
}
