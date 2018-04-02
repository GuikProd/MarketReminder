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

namespace App\Tests\Application\Helper\CloudStorage;

use App\Application\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Application\Helper\CloudStorage\CloudStorageCleanerHelper;
use App\Application\Helper\CloudStorage\Interfaces\CloudStorageCleanerHelperInterface;
use PHPUnit\Framework\TestCase;

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
