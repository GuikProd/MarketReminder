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

namespace App\Tests\Infra\GCP\CloudTranslation\Connector;

use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class FileSystemConnectorSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class FileSystemConnectorSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var ConnectorInterface
     */
    private $fileSystemConnector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->fileSystemConnector = new FileSystemConnector('test');
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testAdapterConnexion()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 295kB', 'FileSystem Connector adapter call memory peak');

        $this->assertBlackfire($configuration, function () {
            $this->fileSystemConnector->getAdapter();
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCacheClear()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory <= 55kB', 'FileSystem Connector cache clear memory peak');

        $this->assertBlackfire($configuration, function () {
            $this->fileSystemConnector->getAdapter()->clear();
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->fileSystemConnector = null;
    }
}
