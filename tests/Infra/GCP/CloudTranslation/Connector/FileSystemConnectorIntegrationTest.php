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

/**
 * Class FileSystemConnectorIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class FileSystemConnectorIntegrationTest extends TestCase
{
    public function testItCanClearTheCache()
    {
        $fileSystemConnector = new FileSystemConnector('test');

        static::assertTrue($fileSystemConnector->getAdapter()->clear());
    }
}
