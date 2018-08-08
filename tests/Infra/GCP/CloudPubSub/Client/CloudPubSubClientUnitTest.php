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

namespace App\Tests\Infra\GCP\CloudPubSub\Client;

use App\Infra\GCP\CloudPubSub\Bridge\Interfaces\CloudPubSubBridgeInterface;
use App\Infra\GCP\CloudPubSub\Client\CloudPubSubClient;
use App\Infra\GCP\CloudPubSub\Client\Interfaces\CloudPubSubClientInterface;
use App\Tests\Infra\GCP\TestCase\TestMessage;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class CloudPubSubClientUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubClientUnitTest extends TestCase
{
    /**
     * @var CloudPubSubBridgeInterface|null
     */
    private $cloudPubSubBridge = null;

    /**
     * @var LoggerInterface|null
     */
    private $logger = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudPubSubBridge = $this->createMock(CloudPubSubBridgeInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testItImplements()
    {
        $client = new CloudPubSubClient($this->cloudPubSubBridge, $this->logger);

        static::assertInstanceOf(CloudPubSubClientInterface::class, $client);
    }

    public function testItPublish()
    {
        $pubSubClientMock = $this->createMock(PubSubClient::class);
        $topicMock = $this->createMock(Topic::class);

        $this->cloudPubSubBridge->method('getPubSubClient')->willReturn($pubSubClientMock);
        $pubSubClientMock->method('topic')->willReturn($topicMock);
        $topicMock->method('publish')->willReturn([]);

        $client = new CloudPubSubClient($this->cloudPubSubBridge, $this->logger);

        $message = new TestMessage();

        $client->publish('test', 'test', $message);
    }
}
