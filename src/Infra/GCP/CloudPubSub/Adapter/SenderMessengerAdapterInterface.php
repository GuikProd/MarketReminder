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

namespace App\Infra\GCP\CloudPubSub\Adapter;

use Symfony\Component\Messenger\Transport\SenderInterface;

/**
 * Interface SenderMessengerAdapterInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface SenderMessengerAdapterInterface extends SenderInterface
{
}
