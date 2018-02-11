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

namespace spec\App\Interactor;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Class UserInteractorSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserInteractorSpec extends ObjectBehavior
{
    public function it_implements()
    {
        $this->shouldImplement(AdvancedUserInterface::class);
    }
}
