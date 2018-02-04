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
 * Class UserCreatedEventSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserCreatedEventSpec extends ObjectBehavior
{
    /**
     * @param UserInterface|\PhpSpec\Wrapper\Collaborator $userInterface
     */
    public function it_implements(UserInterface $userInterface)
    {
        $this->beConstructedWith($userInterface);
        $this->shouldImplement(UserEventInterface::class);
    }

    /**
     * @param UserInterface|\PhpSpec\Wrapper\Collaborator $userInterface
     */
    public function it_should_return(UserInterface $userInterface)
    {
        $this->beConstructedWith($userInterface);
        $this->getUser()->shouldReturn($userInterface);
    }
}
