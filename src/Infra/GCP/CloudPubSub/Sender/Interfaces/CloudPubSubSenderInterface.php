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

namespace App\Infra\GCP\CloudPubSub\Sender\Interfaces;

use App\Infra\GCP\CloudPubSub\Client\Interfaces\CloudPubSubClientInterface;
use Psr\Log\LoggerInterface;

/**
 * Interface CloudPubSubSenderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudPubSubSenderInterface
{
    /**
     * CloudPubSubSenderInterface constructor.
     *
     * @param CloudPubSubClientInterface $cloudPubSubClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        CloudPubSubClientInterface $cloudPubSubClient,
        LoggerInterface $logger
    );

    /**
     * @param object $message
     *
     * @return bool
     */
    public function checkMessage(object $message): bool;
}
