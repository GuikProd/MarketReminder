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

namespace spec\App\Bridge;

use PhpSpec\ObjectBehavior;
use App\Bridge\Interfaces\CloudBridgeInterface;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;

/**
 * Class CloudStorageBridgeSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageBridgeSpec extends ObjectBehavior
{
    public function it_implements()
    {
        $this->beConstructedWith('bucketNameTest');
        $this->shouldImplement(CloudBridgeInterface::class);
        $this->shouldImplement(CloudStorageBridgeInterface::class);
    }
}
