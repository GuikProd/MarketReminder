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

namespace App\Tests\TestCase;

use App\Infra\GCP\CloudStorage\Bridge\CloudStorageBridge;
use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudStorageTestCase.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class CloudStorageTestCase extends TestCase
{
    /**
     * @var CloudStorageBridgeInterface
     */
    protected $cloudStorageBridge;

    public function createCloudStorageBridge()
    {
        $this->cloudStorageBridge = new CloudStorageBridge(
            'credentials.json',
            __DIR__.'./../_credentials'
        );
    }
}
