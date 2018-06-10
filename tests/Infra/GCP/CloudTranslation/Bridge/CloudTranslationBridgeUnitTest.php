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

namespace App\Tests\Infra\GCP\CloudTranslation\Bridge;

use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use Google\Cloud\Translate\TranslateClient;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationBridgeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBridgeUnitTest extends TestCase
{
    /**
     * @var string
     */
    private $cloudTranslationCredentialsFileName;

    /**
     * @var string
     */
    private $cloudTranslationCredentialsFolder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationCredentialsFileName = 'credentials.json';
        $this->cloudTranslationCredentialsFolder = __DIR__.'./../../../../_credentials/';
    }

    public function testItImplements()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->cloudTranslationCredentialsFileName,
            $this->cloudTranslationCredentialsFolder
        );

        static::assertInstanceOf(
            CloudTranslationBridgeInterface::class,
            $cloudTranslationBridge
        );
    }

    public function testCredentialsAreLoaded()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->cloudTranslationCredentialsFileName,
            $this->cloudTranslationCredentialsFolder
        );

        static::assertSame(
            $this->cloudTranslationCredentialsFileName,
            $cloudTranslationBridge->getCredentials()['keyFile']
        );
        static::assertSame(
            $this->cloudTranslationCredentialsFolder,
            $cloudTranslationBridge->getCredentials()['keyFilePath']
        );
    }

    public function testItReturnClient()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->cloudTranslationCredentialsFileName,
            $this->cloudTranslationCredentialsFolder
        );

        static::assertInstanceOf(
            TranslateClient::class,
            $cloudTranslationBridge->getTranslateClient()
        );
    }

    public function testItStopConnexion()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->cloudTranslationCredentialsFileName,
            $this->cloudTranslationCredentialsFolder
        );

        $cloudTranslationBridge->closeConnexion();

        static::assertNull(
            $cloudTranslationBridge->getCredentials()['keyFile']
        );
        static::assertNull(
            $cloudTranslationBridge->getCredentials()['keyFilePath']
        );
    }
}
