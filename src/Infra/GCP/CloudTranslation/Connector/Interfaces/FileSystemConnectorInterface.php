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

namespace App\Infra\GCP\CloudTranslation\Connector\Interfaces;

/**
 * Interface FileSystemConnectorInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface FileSystemConnectorInterface
{
    /**
     * FileSystemConnectorInterface constructor.
     *
     * @param string $namespace
     */
    public function __construct(string $namespace);
}
