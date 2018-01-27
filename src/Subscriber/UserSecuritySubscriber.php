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

namespace App\Subscriber;

use Twig\Environment;
use App\Event\User\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserSecuritySubscriber
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSecuritySubscriber implements EventSubscriberInterface
{
    /**
     * @var Environment
     */
    private $twig;

    private $swiftMailer;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::NAME => 'onUserCreated'
        ];
    }

    public function onUserCreated(UserCreatedEvent $event)
    {
    }
}
