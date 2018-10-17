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

namespace App\Infra\GCP\CloudPubSub\Receiver\Interfaces;

use App\Infra\GCP\CloudPubSub\Client\Interfaces\CloudPubSubClientInterface;

/**
 * Interface CloudPubSubReceiverInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudPubSubReceiverInterface
{
    /**
     * CloudPubSubReceiverInterface constructor.
     *
     * @param CloudPubSubClientInterface $client
     */
    public function __construct(CloudPubSubClientInterface $client);
}
