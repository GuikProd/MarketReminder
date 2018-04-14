<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Infra\GCP\Bridge;

use App\Infra\GCP\Bridge\CloudVisionBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use Google\Cloud\Vision\VisionClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudVisionBridgeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudVisionBridgeTest extends KernelTestCase
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
        static::bootKernel();

        $this->cloudVisionCredentialsFolder = static::$kernel->getContainer()
                                                        ->getParameter('cloud.vision_credentials');

        $this->cloudVisionCredentialsFileName = static::$kernel->getContainer()
                                                               ->getParameter('cloud.vision_credentials.filename');
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
