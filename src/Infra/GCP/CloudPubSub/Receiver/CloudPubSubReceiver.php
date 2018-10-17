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

namespace App\Infra\GCP\CloudPubSub\Receiver;

use App\Infra\GCP\CloudPubSub\Adapter\ReceiverMessengerAdapterInterface;
use App\Infra\GCP\CloudPubSub\Client\Interfaces\CloudPubSubClientInterface;
use App\Infra\GCP\CloudPubSub\Receiver\Interfaces\CloudPubSubReceiverInterface;

/**
 * Class CloudPubSubReceiver.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubReceiver implements CloudPubSubReceiverInterface, ReceiverMessengerAdapterInterface
{
    /**
     * @var CloudPubSubClientInterface
     */
    private $cloudPubSubClient;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudPubSubClientInterface $cloudPubSubClient)
    {
        $this->cloudPubSubClient = $cloudPubSubClient;
    }

    /**
     * {@inheritdoc}
     */
    public function receive(callable $handler): void
    {

    }

    /**
     * {@inheritdoc}
     */
    public function stop(): void
    {
    }
}
