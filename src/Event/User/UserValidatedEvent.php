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

use App\Models\Interfaces\UserInterface;
use App\Event\Interfaces\UserEventInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserValidatedEvent.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserValidatedEvent extends Event implements UserEventInterface
{
    const NAME = 'user.validated';

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * UserValidatedEvent constructor.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
