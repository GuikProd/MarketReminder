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

namespace App\Event\User;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserCreatedEvent
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserCreatedEvent extends Event
{
    const NAME = 'user.registered';


    private $user;

}
