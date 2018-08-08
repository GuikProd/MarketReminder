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

namespace App\Infra\GCP\CloudPubSub\Client\Interfaces;

use App\Infra\GCP\CloudPubSub\Bridge\Interfaces\CloudPubSubBridgeInterface;
use App\Infra\GCP\CloudPubSub\Message\Interfaces\MessageInterface;
use Psr\Log\LoggerInterface;

/**
 * Interface CloudPubSubClientInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudPubSubClientInterface
{
    /**
     * CloudPubSubClientInterface constructor.
     *
     * @param CloudPubSubBridgeInterface $bridge
     * @param LoggerInterface $logger
     */
    public function __construct(CloudPubSubBridgeInterface $bridge, LoggerInterface $logger);

    /**
     * @param string $topic
     * @param string $key
     * @param MessageInterface $message
     */
    public function publish(string $topic, string $key, MessageInterface $message): void;
}
