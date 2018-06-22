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
use Google\Cloud\Vision\VisionClient;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudVisionBridgeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionBridgeUnitTest extends TestCase
{
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
    }

    public function testItImplements()
    {
        $cloudVisionBridge = new CloudVisionBridge(
            $this->cloudVisionCredentialsFileName,
            $this->cloudVisionCredentialsFolder,
            $this->credentialsLoader
        );

        static::assertInstanceOf(
            CloudVisionBridgeInterface::class,
            $cloudVisionBridge
        );
    }

    public function testReturnServiceBuilder()
    {
        $cloudVisionBridge = new CloudVisionBridge(
            $this->cloudVisionCredentialsFileName,
            $this->cloudVisionCredentialsFolder,
            $this->credentialsLoader
        );

        static::assertInstanceOf(
            VisionClient::class,
            $cloudVisionBridge->getVisionClient()
        );
    }
}
