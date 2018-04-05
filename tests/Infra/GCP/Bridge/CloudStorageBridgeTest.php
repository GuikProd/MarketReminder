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

use App\Infra\GCP\Bridge\CloudStorageBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
use Google\Cloud\Core\ServiceBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudStorageBridgeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageBridgeTest extends KernelTestCase
{
    /**
     * @var string
     */
    private $bucketCredentials;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->bucketCredentials = static::bootKernel()->getContainer()
                                                       ->getParameter('cloud.storage_credentials');
    }

    public function testReturnServiceBuilder()
    {
        $cloudStorage = new CloudStorageBridge($this->bucketCredentials);

        static::assertInstanceOf(
            ServiceBuilder::class,
            $cloudStorage->getServiceBuilder()
        );
    }

    public function testCredentialsAreLoaded()
    {
        $cloudStorageBridge = new CloudStorageBridge($this->bucketCredentials);

        static::assertInstanceOf(
            CloudStorageBridgeInterface::class,
            $cloudStorageBridge->loadCredentialsFile()
        );
    }

    public function testConnexionIsDown()
    {
        $cloudStorageBridge = new CloudStorageBridge($this->bucketCredentials);

        $cloudStorageBridge->closeConnexion();

        static::assertNull(
            $cloudStorageBridge->getCredentials()
        );
    }
}
