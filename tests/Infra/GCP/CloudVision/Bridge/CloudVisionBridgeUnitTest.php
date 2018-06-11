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

use App\Infra\GCP\Bridge\CloudVisionBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
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
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->cloudVisionCredentialsFolder = __DIR__.'./../../../../_credentials';
        $this->cloudVisionCredentialsFileName = 'credentials.json';
    }

    public function testItImplements()
    {
        $cloudVisionBridge = new CloudVisionBridge(
            $this->cloudVisionCredentialsFileName,
            $this->cloudVisionCredentialsFolder
        );

        static::assertInstanceOf(
            CloudVisionBridgeInterface::class,
            $cloudVisionBridge
        );
    }

    /**
     * @throws \Google\Cloud\Core\Exception\GoogleException
     */
    public function testReturnServiceBuilder()
    {
        $cloudVisionBridge = new CloudVisionBridge(
            $this->cloudVisionCredentialsFileName,
            $this->cloudVisionCredentialsFolder
        );

        static::assertInstanceOf(
            VisionClient::class,
            $cloudVisionBridge->getVisionClient()
        );
    }

    public function testCredentialsAreLoaded()
    {
        $cloudVisionBridge = new CloudVisionBridge(
            $this->cloudVisionCredentialsFileName,
            $this->cloudVisionCredentialsFolder
        );

        static::assertArrayHasKey(
            'keyFilePath',
            $cloudVisionBridge->getCredentials()
        );
    }

    public function testConnexionIsDown()
    {
        $cloudVisionBridge = new CloudVisionBridge(
            $this->cloudVisionCredentialsFileName,
            $this->cloudVisionCredentialsFolder
        );

        $cloudVisionBridge->closeConnexion();

        static::assertNull(
            $cloudVisionBridge->getCredentials()['keyFilePath']
        );
    }
}
