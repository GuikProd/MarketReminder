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

namespace tests\Helper\CloudStorage;

use PHPUnit\Framework\TestCase;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Helper\CloudStorage\CloudStorageCleanerHelper;
use App\Helper\Interfaces\CloudStorage\CloudStorageCleanerHelperInterface;

/**
 * Class CloudStorageCleanerHelperTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageCleanerHelperTest extends TestCase
{
    public function testInterfaceImplementation()
    {
        $cloudStorageBridgeMock = $this->createMock(CloudStorageBridgeInterface::class);

        $cloudStorageCleanerHelper = new CloudStorageCleanerHelper($cloudStorageBridgeMock);

        static::assertInstanceOf(
            CloudStorageCleanerHelperInterface::class,
            $cloudStorageCleanerHelper
        );
    }
}
