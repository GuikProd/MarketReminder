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
use Google\Cloud\Translate\TranslateClient;
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
    private $translationCredentialsFileName;

    /**
     * @var string
     */
    private $translationCredentialsFolder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->translationCredentialsFileName = static::$kernel->getContainer()
                                                               ->getParameter('cloud.translation_credentials.filename');

        $this->translationCredentialsFolder = static::$kernel->getContainer()
                                                             ->getParameter('cloud.translation_credentials');
    }

    public function testItImplements()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->translationCredentialsFileName,
            $this->translationCredentialsFolder
        );

        static::assertInstanceOf(
            CloudTranslationBridgeInterface::class,
            $cloudTranslationBridge
        );
    }

    public function testCredentialsAreLoaded()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->translationCredentialsFileName,
            $this->translationCredentialsFolder
        );

        static::assertSame(
            $this->translationCredentialsFileName,
            $cloudTranslationBridge->getCredentials()['keyFile']
        );
    }

    public function testItReturnTranslateClient()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->translationCredentialsFileName,
            $this->translationCredentialsFolder
        );

        static::assertInstanceOf(
            TranslateClient::class,
            $cloudTranslationBridge->getTranslateClient()
        );
    }

    public function testItStopConnexion()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->translationCredentialsFileName,
            $this->translationCredentialsFolder
        );

        $cloudTranslationBridge->closeConnexion();

        static::assertNull(
            $cloudTranslationBridge->getCredentials()
        );
    }
}
