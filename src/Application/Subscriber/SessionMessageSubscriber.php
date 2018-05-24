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

namespace App\Application\Subscriber;

use App\Application\Event\Interfaces\SessionMessageEventInterface;
use App\Application\Subscriber\Interfaces\SessionMessageSubscriberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SessionMessageSubscriber
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class SessionMessageSubscriber implements EventSubscriberInterface, SessionMessageSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator
    ) {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SessionMessageEventInterface::NAME => 'onSessionMessage'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onSessionMessage(SessionMessageEventInterface $event): void
    {
        if ($event->getMessage() === '' || !$event->getFlashBag()) {
            return;
        }

        $this->session->getFlashBag()
                      ->add(
                          $event->getFlashBag(),
                          $this->translator
                               ->trans($event->getMessage(), [], 'messages')
                      );
    }
}
