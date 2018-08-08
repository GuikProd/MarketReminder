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

namespace App\Tests\Infra\GCP\CloudPubSub\Bridge;

use App\Infra\GCP\Bridge\Interfaces\CloudBridgeInterface;
use App\Infra\GCP\CloudPubSub\Bridge\CloudPubSubBridge;
use App\Infra\GCP\CloudPubSub\Bridge\Interfaces\CloudPubSubBridgeInterface;
use App\Infra\GCP\Loader\CredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use Google\Cloud\PubSub\PubSubClient;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudPubSubBridgeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubBridgeUnitTest extends TestCase
{
    /**
     * @var string
     */
    private $cloudCredentialsFilename = null;

    /**
     * @var string
     */
    private $cloudCredentialsFolder = null;

    /**
     * @var LoaderInterface|null
     */
    private $credentialsLoader = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudCredentialsFilename = 'credentials.json';
        $this->cloudCredentialsFolder = __DIR__.'/../../../../_credentials/';
        $this->credentialsLoader = new CredentialsLoader();
    }

    public function testItImplements()
    {
        $bridge = new CloudPubSubBridge(
            $this->cloudCredentialsFilename,
            $this->cloudCredentialsFolder,
            $this->credentialsLoader
        );

        static::assertInstanceOf(CloudPubSubBridgeInterface::class, $bridge);
        static::assertInstanceOf(CloudBridgeInterface::class, $bridge);
    }

    public function testItReturnClient()
    {
        $bridge = new CloudPubSubBridge(
            $this->cloudCredentialsFilename,
            $this->cloudCredentialsFolder,
            $this->credentialsLoader
        );

        static::assertInstanceOf(PubSubClient::class, $bridge->getPubSubClient());
    }
}
