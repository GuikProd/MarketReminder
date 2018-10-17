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

namespace App\Infra\GCP\CloudPubSub\Client;

use App\Infra\GCP\CloudPubSub\Bridge\Interfaces\CloudPubSubBridgeInterface;
use App\Infra\GCP\CloudPubSub\Client\Interfaces\CloudPubSubClientInterface;
use App\Infra\GCP\CloudPubSub\Message\Interfaces\MessageInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CloudPubSubClient.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubClient implements CloudPubSubClientInterface
{
    /**
     * @var CloudPubSubBridgeInterface
     */
    private $cloudPubSubBridge;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudPubSubBridgeInterface $cloudPubSubBridge,
        LoggerInterface $logger
    ) {
        $this->cloudPubSubBridge = $cloudPubSubBridge;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(string $topic, string $key, MessageInterface $message): void
    {
        $pubSubClient = $this->cloudPubSubBridge->getPubSubClient();

        $toPublishTopic = $pubSubClient->topic($topic);

        $toPublishTopic->publish(['data' => serialize($message->getData())]);

        $this->logger->info(sprintf('A new message has been published : %s', $message->getIdentifier()));
    }

    /**
     * {@inheritdoc}
     */
    public function receive(string $subscription): void
    {
        $pubSubClient = $this->cloudPubSubBridge->getPubSubClient();

        $subscription = $pubSubClient->subscription($subscription);
    }
}
