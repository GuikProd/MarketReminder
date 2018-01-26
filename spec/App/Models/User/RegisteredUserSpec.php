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

namespace spec\App\Models\User;

use PhpSpec\ObjectBehavior;
use App\Models\Interfaces\RegisteredUserInterface;

/**
 * Class RegisteredUserSpec
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisteredUserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldImplement(RegisteredUserInterface::class);
    }
}
