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

namespace spec\App\Event\User;

use PhpSpec\ObjectBehavior;
use App\Models\Interfaces\UserInterface;
use App\Event\Interfaces\UserEventInterface;

/**
 * Class UserValidatedEventSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserValidatedEventSpec extends ObjectBehavior
{
    /**
     * @param UserInterface|\PhpSpec\Wrapper\Collaborator $user
     */
    public function it_implements(UserInterface $user)
    {
        $this->beConstructedWith($user);
        $this->shouldImplement(UserEventInterface::class);
    }

    /**
     * @param UserInterface $user
     */
    public function should_return_user(UserInterface $user)
    {
        $this->beConstructedWith($user);
        $this->getUser()->shouldReturn($user);
    }
}
