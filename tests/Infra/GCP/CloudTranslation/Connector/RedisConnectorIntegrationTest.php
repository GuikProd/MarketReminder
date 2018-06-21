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
use PHPUnit\Framework\TestCase;

/**
 * Class RedisConnectorIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RedisConnectorIntegrationTest extends TestCase
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
        $this->redisDSN = getenv('REDIS_TEST_URL');
        $this->redisNamespace = 'test';
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
