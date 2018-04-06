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

namespace App\Tests\Infra\GCP\CloudVision;

use App\Infra\GCP\CloudVision\CloudVisionAnalyserHelper;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudVisionAnalyserHelperTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudVisionAnalyserHelperTest extends TestCase
{
    public function testImplements()
    {
        $cloudVisionBridgeMock = $this->createMock(CloudVisionBridgeInterface::class);

        $cloudVisionAnalyserHelper = new CloudVisionAnalyserHelper($cloudVisionBridgeMock);

        static::assertInstanceOf(
            CloudVisionAnalyserHelperInterface::class,
            $cloudVisionAnalyserHelper
        );
    }
}
