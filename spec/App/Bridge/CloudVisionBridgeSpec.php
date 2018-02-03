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
use App\Bridge\Interfaces\CloudVisionBridgeInterface;

/**
 * Class CloudVisionBridgeSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudVisionBridgeSpec extends ObjectBehavior
{
    public function it_implements()
    {
        $this->beConstructedWith('visionCredentialsFolderTest');
        $this->shouldImplement(CloudVisionBridgeInterface::class);
    }
}
