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

namespace App\Tests\Infra\GCP\Bridge;

use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudStorageBridgeSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBridgeSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationBridge = new CloudTranslationBridge(
            'credentials.json',
            __DIR__.'./../../../../_credentials'
        );
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCredentialsLoading()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 5kB', 'CloudTranslation bridge credentials loading memory');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationBridge->getCredentials();
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testTranslationClientIsReturned()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 600kB', 'CloudTranslation client creation memory');
        $configuration->assert('main.network_in == 0B', 'CloudTranslation client creation network in');
        $configuration->assert('main.network_out == 0B', 'CloudTranslation client creation network out');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationBridge->getTranslateClient();
        });
    }
}
