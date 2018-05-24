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

namespace App\Tests\Infra\GCP\CloudTranslation\Connector;

use App\Infra\GCP\CloudTranslation\Connector\ApcuConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ApcuConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class ApcuConnectorUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ApcuConnectorUnitTest extends TestCase
{
    public function testItImplements()
    {
        $apcuConnector = new ApcuConnector('test');

        static::assertInstanceOf(
            ConnectorInterface::class,
            $apcuConnector
        );
        static::assertInstanceOf(
            ApcuConnectorInterface::class,
            $apcuConnector
        );
    }

    public function testItReturnAdapter()
    {
        $apcuConnector = new ApcuConnector('test');

        static::assertInstanceOf(TagAwareAdapterInterface::class,
            $apcuConnector->getAdapter()
        );
    }
}
