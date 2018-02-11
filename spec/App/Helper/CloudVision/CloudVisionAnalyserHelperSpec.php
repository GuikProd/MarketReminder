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

namespace spec\App\Helper\CloudVision;

use PhpSpec\ObjectBehavior;
use App\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;

/**
 * Class CloudVisionAnalyserHelperSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudVisionAnalyserHelperSpec extends ObjectBehavior
{
    public function it_implements(CloudVisionBridgeInterface $cloudVisionBridge)
    {
        $this->beConstructedWith($cloudVisionBridge);
        $this->shouldImplement(CloudVisionAnalyserHelperInterface::class);
    }
}
