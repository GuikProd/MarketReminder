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

namespace App\Infra\GCP\CloudTranslation\Connector;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\BackupConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use Predis\Client;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class RedisConnector.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RedisConnector implements RedisConnectorInterface, ConnectorInterface, BackupConnectorInterface
{
    /**
     * @var TagAwareAdapterInterface
     */
    private $adapter;

    /**
     * @var bool
     */
    private $backup = false;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $redisDSN;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $redisDSN,
        string $namespace
    ) {
        $this->redisDSN = $redisDSN;
        $this->namespace = $namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter(): TagAwareAdapterInterface
    {
        $connexion = new Client($this->redisDSN);

        $redisAdapter = new RedisAdapter(
            $connexion,
            $this->namespace,
            0
        );

        $redisAdapter::createConnection($this->redisDSN);

        $this->adapter = new TagAwareAdapter(
            $redisAdapter
        );

        return $this->adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function setBackup(bool $backup): void
    {
        $this->backup = $backup;
    }

    /**
     * {@inheritdoc}
     */
    public function isBackup(): bool
    {
        return $this->backup;
    }
}
