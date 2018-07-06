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

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class RedisConnectorSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RedisConnectorSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var ConnectorInterface
     */
    private $redisConnector = null;

    /**
     * @var string
     */
    private $redisDSN = null;

    /**
     * @var string
     */
    private $redisNamespace = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->redisDSN = getenv('REDIS_TEST_URL');
        $this->redisNamespace = 'test';

        $this->redisConnector = new RedisConnector(
            $this->redisDSN,
            $this->redisNamespace
        );
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testAdapterInstantiation()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 500kB', 'Connector adapter call memory peak');
        $configuration->assert('main.network_in == 0', 'Connector adapter call network in');
        $configuration->assert('main.network_out == 0', 'Connector adapter call network out');

        $this->assertBlackfire($configuration, function () {
            $this->redisConnector->getAdapter();
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCacheCleaning()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 170kB', 'Connector clear memory peak');
        $configuration->assert('main.network_in < 500B', 'Connector clean network in');
        $configuration->assert('main.network_out < 100B', 'Connector clean network out');

        $this->assertBlackfire($configuration, function () {
            $this->redisConnector->getAdapter()->clear();
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->redisDSN = null;
        $this->redisNamespace = null;
        $this->redisConnector = null;
    }
}
