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

namespace App\Tests\Infra\GCP\CloudVision\Bridge;

use App\Infra\GCP\CloudVision\Bridge\CloudVisionBridge;
use App\Infra\GCP\CloudVision\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Infra\GCP\Loader\CredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudVisionBridgeSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionBridgeSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var CloudVisionBridgeInterface
     */
    private $cloudVisionBridge;

    /**
     * @var string
     */
    private $cloudVisionCredentialsFileName;

    /**
     * @var string
     */
    private $cloudVisionCredentialsFolder;

    /**
     * @var LoaderInterface
     */
    private $credentialsLoader;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudVisionCredentialsFolder = __DIR__.'/../../../../_credentials';
        $this->cloudVisionCredentialsFileName = 'credentials.json';
        $this->credentialsLoader = new CredentialsLoader();

        $this->cloudVisionBridge = new CloudVisionBridge(
            $this->cloudVisionCredentialsFileName,
            $this->cloudVisionCredentialsFolder,
            $this->credentialsLoader
        );
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItReturnVisionClient()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 0.9MB', 'CloudVision client memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->cloudVisionBridge->getVisionClient();
        });
    }
}
