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

namespace App\Application\Symfony\Subscriber;

use App\Application\Symfony\Events\SessionMessageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SessionMessageSubscriber
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class SessionMessageSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * SessionMessageSubscriber constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SessionMessageEvent::NAME => 'onSessionMessage'
        ];
    }

    public function onSessionMessage(SessionMessageEvent $event)
    {
        if ($event->getMessage() === '') {
            return;
        }
    }
}
