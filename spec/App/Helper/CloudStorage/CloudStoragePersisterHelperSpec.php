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

namespace spec\App\Helper\CloudStorage;

use PhpSpec\ObjectBehavior;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Helper\Interfaces\CloudStorage\CloudStoragePersisterHelperInterface;

/**
 * Class CloudStoragePersisterHelperSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStoragePersisterHelperSpec extends ObjectBehavior
{
    public function it_implements(CloudStorageBridgeInterface $cloudStorageBridge)
    {
        $this->beConstructedWith($cloudStorageBridge);
        $this->shouldImplement(CloudStoragePersisterHelperInterface::class);
    }
}
