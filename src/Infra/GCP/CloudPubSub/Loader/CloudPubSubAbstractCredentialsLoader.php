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

namespace App\Infra\GCP\CloudPubSub\Loader;

use App\Infra\GCP\CloudPubSub\Loader\Interfaces\CloudPubSubCredentialsLoaderInterface;
use App\Infra\GCP\Loader\AbstractCredentialsLoader;

/**
 * Class CloudPubSubAbstractCredentialsLoader.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubAbstractCredentialsLoader extends AbstractCredentialsLoader implements CloudPubSubCredentialsLoaderInterface
{
}
