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

use App\Infra\GCP\CloudTranslation\Connector\BackUp\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\BackUp\Interfaces\BackUpConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\BackUp\Interfaces\FileSystemConnectorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class FileSystemConnectorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class FileSystemConnectorUnitTest extends TestCase
{
    public function testItImplementsAndExtends()
    {
        $fileSystemConnector = new FileSystemConnector('test');

        static::assertInstanceOf(
            BackUpConnectorInterface::class,
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
        $fileSystemConnector->activate(true);

        static::assertInstanceOf(
            CacheItemPoolInterface::class,
            $fileSystemConnector->getBackUpAdapter()
        );
    }
}
