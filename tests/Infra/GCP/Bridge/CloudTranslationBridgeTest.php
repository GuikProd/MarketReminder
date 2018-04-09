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

use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use Google\Cloud\Core\ServiceBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationBridgeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationBridgeTest extends KernelTestCase
{
    /**
     * @var string
     */
    private $translationCredentials;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->translationCredentials = static::$kernel->getContainer()
                                                       ->getParameter('cloud.translation_credentials');
    }

    public function testItImplements()
    {
        $cloudTranslationBridge = new CloudTranslationBridge($this->translationCredentials);

        static::assertInstanceOf(
            CloudTranslationBridgeInterface::class,
            $cloudTranslationBridge
        );
    }

    public function testCredentialsAreLoaded()
    {
        $cloudTranslationBridge = new CloudTranslationBridge($this->translationCredentials);

        static::assertInstanceOf(
            CloudTranslationBridgeInterface::class,
            $cloudTranslationBridge->loadCredentialsFile()
        );
    }

    public function testItReturnServiceBuilder()
    {
        $cloudTranslationBridge = new CloudTranslationBridge($this->translationCredentials);

        static::assertInstanceOf(
            ServiceBuilder::class,
            $cloudTranslationBridge->getServiceBuilder()
        );
    }

    public function testItStopConnexion()
    {
        $cloudTranslationBridge = new CloudTranslationBridge($this->translationCredentials);

        $cloudTranslationBridge->closeConnexion();

        static::assertNull(
            $cloudTranslationBridge->getCredentials()
        );
    }
}
