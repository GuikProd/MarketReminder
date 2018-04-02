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

namespace App\Tests\Application\Bridge;

use App\Application\Bridge\CloudVisionBridge;
use App\Application\Bridge\Interfaces\CloudVisionBridgeInterface;
use Google\Cloud\Core\ServiceBuilder;
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
    private $visionCredentials;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->visionCredentials = static::bootKernel()->getContainer()
                                                       ->getParameter('cloud.vision_credentials');
    }

    public function testReturnServiceBuilder()
    {
        $cloudVisionBridge = new CloudVisionBridge($this->visionCredentials);

        static::assertInstanceOf(
            ServiceBuilder::class,
            $cloudVisionBridge->getServiceBuilder()
        );
    }

    public function testCredentialsAreLoaded()
    {
        $cloudVisionBridge = new CloudVisionBridge($this->visionCredentials);

        static::assertInstanceOf(
            CloudVisionBridgeInterface::class,
            $cloudVisionBridge->loadCredentialsFile()
        );
    }

    public function testConnexionIsDown()
    {
        $cloudVisionBridge = new CloudVisionBridge($this->visionCredentials);

        $cloudVisionBridge->closeConnexion();

        static::assertNull(
            $cloudVisionBridge->getCredentials()
        );
    }
}
