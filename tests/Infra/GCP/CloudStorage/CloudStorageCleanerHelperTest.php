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

namespace App\Tests\Infra\GCP\CloudStorage;

use App\Infra\GCP\CloudStorage\CloudStorageCleanerHelper;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStorageCleanerHelperInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
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
