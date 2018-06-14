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

use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisConnectorIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class RedisConnectorIntegrationTest extends KernelTestCase
{
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
    }

    public function testCacheCanBeCleaned()
    {
        $redisConnector = new RedisConnector(
            $this->redisDSN,
            $this->redisNamespace
        );

        static::assertTrue($redisConnector->getAdapter()->clear());
    }
}
