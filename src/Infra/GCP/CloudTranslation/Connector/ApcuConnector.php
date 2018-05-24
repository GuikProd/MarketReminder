<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infra\GCP\CloudTranslation\Connector;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ApcuConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class ApcuConnector.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ApcuConnector implements ApcuConnectorInterface, ConnectorInterface
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter(): TagAwareAdapterInterface
    {
        $apcuAdapter = new ApcuAdapter($this->namespace);

        return new TagAwareAdapter(
            $apcuAdapter
        );
    }
}
