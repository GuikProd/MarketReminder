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
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\BackupConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\FileSystemConnectorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class FileSystemConnectorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class FileSystemConnectorUnitTest extends TestCase
{
    public function testItImplementsAndExtends()
    {
        $fileSystemConnector = new FileSystemConnector('test');

        static::assertInstanceOf(
            ConnectorInterface::class,
            $fileSystemConnector
        );
        static::assertInstanceOf(
            BackupConnectorInterface::class,
            $fileSystemConnector
        );
        static::assertInstanceOf(
            FileSystemConnectorInterface::class,
            $fileSystemConnector
        );
    }

    public function testItReturnAdapter()
    {
        $fileSystemConnector = new FileSystemConnector('test');

        static::assertInstanceOf(
            TagAwareAdapterInterface::class,
            $fileSystemConnector->getAdapter()
        );
    }

    public function testItShouldBeABackup()
    {
        $fileSystemConnector = new FileSystemConnector('test');
        $fileSystemConnector->setBackup(true);

        static::assertTrue($fileSystemConnector->isBackup());
    }
}
