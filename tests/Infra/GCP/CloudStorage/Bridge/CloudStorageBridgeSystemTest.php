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

namespace App\Tests\Infra\GCP\CloudStorage\Bridge;

use App\Infra\GCP\CloudStorage\Bridge\CloudStorageBridge;
use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudStorageBridgeSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudStorageBridgeSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var string
     */
    private $bucketCredentialsFolder;

    /**
     * @var string
     */
    private $bucketCredentialsFileName;

    /**
     * @var CloudStorageBridgeInterface
     */
    private $cloudStorageBridge;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->bucketCredentialsFolder = __DIR__.'./../../../../_credentials';
        $this->bucketCredentialsFileName = 'credentials.json';

        $this->cloudStorageBridge = new CloudStorageBridge(
            $this->bucketCredentialsFileName,
            $this->bucketCredentialsFolder
        );
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItCreateBridge()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1.5MB', 'CloudStorageBridge creation memory usage');
        $configuration->assert('metrics.http.requests.count == 0', 'CloudStorageBridge creation HTTP Request');

        $this->assertBlackfire($configuration, function () {
            $this->cloudStorageBridge->getStorageClient();
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItCloseConnexion()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 5kB', 'CloudStorageBridge close connexion memory usage');
        $configuration->assert('metrics.http.requests.count == 0', 'CloudStorageBridge close connexion HTTP Request');

        $this->assertBlackfire($configuration, function () {
           $this->cloudStorageBridge->closeConnexion();
        });
    }
}
