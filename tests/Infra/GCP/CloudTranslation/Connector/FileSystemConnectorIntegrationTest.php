<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Infra\GCP\CloudTranslation\Connector;

use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class FileSystemConnectorIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class FileSystemConnectorIntegrationTest extends TestCase
{
    public function testItReturnAdapter()
    {
        $fileSystemConnector = new FileSystemConnector('test');

        static::assertInstanceOf(
            TagAwareAdapterInterface::class,
            $fileSystemConnector->getAdapter()
        );
    }

    public function testItCanClearTheCache()
    {
        $fileSystemConnector = new FileSystemConnector('test');

        static::assertTrue($fileSystemConnector->getAdapter()->clear());
    }

    public function testItShouldBeABackup()
    {
        $fileSystemConnector = new FileSystemConnector('test');
        $fileSystemConnector->setBackup(true);

        static::assertTrue($fileSystemConnector->isBackup());
    }
}
