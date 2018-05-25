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
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class ApcuConnectorIntegrationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ApcuConnectorIntegrationTest extends TestCase
{
    /**
     * @var ApcuConnectorInterface
     */
    private $apcuConnector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->apcuConnector = new ApcuConnector('test');
    }

    public function testItReturnAdapter()
    {
        static::assertInstanceOf(
            TagAwareAdapterInterface::class,
            $this->apcuConnector->getAdapter()
        );
    }
}
