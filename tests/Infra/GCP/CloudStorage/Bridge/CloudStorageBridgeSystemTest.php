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
use App\Infra\GCP\Loader\CredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
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
     * @var LoaderInterface
     */
    private $credentialsLoader;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->bucketCredentialsFolder = __DIR__.'/../../../../_credentials';
        $this->bucketCredentialsFileName = 'credentials.json';
        $this->credentialsLoader = new CredentialsLoader();

        $this->cloudStorageBridge = new CloudStorageBridge(
            $this->bucketCredentialsFileName,
            $this->bucketCredentialsFolder,
            $this->credentialsLoader
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
}
