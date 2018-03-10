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

namespace App\Infra\Form\FormSubscriber;

use App\Domain\Models\User;
use App\Infra\Form\FormSubscriber\Interfaces\AskResetPasswordTypeSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AskResetPasswordTypeSubscriber
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeSubscriber implements EventSubscriberInterface, AskResetPasswordTypeSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AskResetPasswordTypeSubscriber constructor.
     *
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager
    ) {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
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
        $user = $this->entityManager->getRepository(User::class)
                                    ->getUserByUsernameAndEmail(
                                        $event->getData()->username,
                                        $event->getData()->email
                                    );

        if (!$user) {
            $event->getForm()->addError(
                new FormError(
                    $this->translator
                         ->trans('user.not_found', [], 'messages')
                )
            );
        }
    }
}
