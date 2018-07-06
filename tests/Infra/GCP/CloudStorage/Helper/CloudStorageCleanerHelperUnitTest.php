<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Infra\GCP\CloudStorage;

use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageCleanerHelper;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageCleanerHelperInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudStorageCleanerHelperUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudStorageCleanerHelperUnitTest extends TestCase
{
    public function testItImplements()
    {
        $cloudStorageBridgeMock = $this->createMock(CloudStorageBridgeInterface::class);

        $cloudStorageCleanerHelper = new CloudStorageCleanerHelper($cloudStorageBridgeMock);

        static::assertInstanceOf(
            CloudStorageCleanerHelperInterface::class,
            $cloudStorageCleanerHelper
        );
    }
}
