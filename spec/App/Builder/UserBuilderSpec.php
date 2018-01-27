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

namespace spec\App\Builder;

use PhpSpec\ObjectBehavior;
use App\Builder\UserBuilder;
use App\Interactor\UserInteractor;
use App\Models\Interfaces\UserInterface;
use App\Builder\Interfaces\UserBuilderInterface;

/**
 * Class UserBuilder
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserBuilderSpec extends ObjectBehavior
{
    public function it_implement()
    {
        $this->shouldImplement(UserBuilderInterface::class);
    }

    public function it_should_return()
    {
        $this->createUser()->shouldReturn(UserBuilder::class);
        $this->createUser()->shouldImplement(UserBuilderInterface::class);
    }

    public function it_should_return_user()
    {
        $this->createUser()->getUser()->shouldReturn(UserInteractor::class);
        $this->createUser()->getUser()->shouldImplement(UserInterface::class);
    }
}
