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

namespace tests\Bridge;

use App\Bridge\CloudStorageBridge;
use Google\Cloud\Core\ServiceBuilder;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudStorageBridgeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageBridgeTest extends KernelTestCase
{
    use TestCaseTrait;

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

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testBlackfireServiceBuilder()
    {

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
