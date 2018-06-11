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

namespace App\Tests\Infra\GCP\CloudStorage\Helper;

use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageWriterHelper;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudStorageWriterUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudStorageWriterUnitTest extends TestCase
{
    /**
     * @var CloudStorageBridgeInterface
     */
    private $cloudStorageBridge;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudStorageBridge = $this->createMock(CloudStorageBridgeInterface::class);
    }

    public function testItImplements()
    {
        $cloudStorageWriter = new CloudStorageWriterHelper($this->cloudStorageBridge);

        static::assertInstanceOf(
            CloudStorageWriterHelperInterface::class,
            $cloudStorageWriter
        );
    }
}
