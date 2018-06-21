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
use App\Infra\GCP\Loader\CredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
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
    private $cloudTranslationCredentialsFileName = null;

    /**
     * @var string
     */
    private $cloudTranslationCredentialsFolder = null;

    /**
     * @var LoaderInterface
     */
    private $credentialsLoader;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationCredentialsFileName = 'credentials.json';
        $this->cloudTranslationCredentialsFolder = __DIR__.'/../../../../_credentials/';
        $this->credentialsLoader = new CredentialsLoader();
    }

    public function testItImplements()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->cloudTranslationCredentialsFileName,
            $this->cloudTranslationCredentialsFolder,
            $this->credentialsLoader
        );

        static::assertInstanceOf(
            CloudTranslationBridgeInterface::class,
            $cloudTranslationBridge
        );
    }

    public function testItReturnClient()
    {
        $cloudTranslationBridge = new CloudTranslationBridge(
            $this->cloudTranslationCredentialsFileName,
            $this->cloudTranslationCredentialsFolder,
            $this->credentialsLoader
        );

        static::assertInstanceOf(
            TranslateClient::class,
            $cloudTranslationBridge->getTranslateClient()
        );
    }
}
