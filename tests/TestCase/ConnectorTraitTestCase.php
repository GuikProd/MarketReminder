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

namespace App\Tests\TestCase;

use App\Infra\GCP\CloudTranslation\Connector\BackUp\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\BackUp\Interfaces\BackUpConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;

/**
 * Class ConnectorTraitTestCase.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
trait ConnectorTraitTestCase
{
    /**
     * @var BackUpConnectorInterface
     */
    protected $backUpConnector;

    /**
     * @var ConnectorInterface
     */
    protected $connector;

    protected function createRedisConnector()
    {
        $this->connector = new RedisConnector(
            getenv('REDIS_TEST_URL'),
            'test'
        );

        $this->connector->getAdapter()->clear();
    }

    protected function createFileSystemBackUp()
    {
        $this->backUpConnector = new FileSystemConnector('backup_test');
        $this->backUpConnector->activate(true);

        $this->backUpConnector->getBackUpAdapter()->clear();
    }
}
