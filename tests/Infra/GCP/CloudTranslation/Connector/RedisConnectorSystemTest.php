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

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisConnectorSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class RedisConnectorSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var string
     */
    private $redisDSN;

    /**
     * @var string
     */
    private $redisNamespace;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->redisDSN = static::$kernel->getContainer()->getParameter('redis.test_dsn');
        $this->redisNamespace = static::$kernel->getContainer()->getParameter('redis.namespace_test');

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
        $configuration->assert('main.peak_memory < 610kB', 'Connector adapter call memory peak');
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
        $configuration->assert('main.peak_memory < 150kB', 'Connector clear memory peak');
        $configuration->assert('main.network_in < 550B', 'Connector clean network in');
        $configuration->assert('main.network_out < 200B', 'Connector clean network out');

        $this->assertBlackfire($configuration, function () {
            $this->redisConnector->getAdapter()->clear();
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItShouldBeABackup()
    {
        $configuration = new Configuration();
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 5kB', 'Connector backup memory peak');
        $configuration->assert('main.network_in == 0B', 'Connector backup network in');
        $configuration->assert('main.network_out == 0B', 'Connector backup network out');

        $this->assertBlackfire($configuration, function () {
            $this->redisConnector->setBackup(true);
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->redisConnector = null;
    }
}
