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

namespace App\Tests\Infra\GCP\Bridge;

use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudStorageBridgeSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBridgeSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var string
     */
    private $cloudTranslationAPIEntryPoint;

    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

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
    protected function setUp()
    {
        static::bootKernel();

        $this->cloudTranslationAPIEntryPoint = static::$container->getParameter('cloud.translation.api_entrypoint');
        $this->translationCredentialsFileName = static::$container->getParameter('cloud.translation_credentials.filename');
        $this->translationCredentialsFolder = static::$container->getParameter('cloud.translation_credentials');

        $this->cloudTranslationBridge = new CloudTranslationBridge(
            $this->cloudTranslationAPIEntryPoint,
            $this->translationCredentialsFileName,
            $this->translationCredentialsFolder
        );
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCredentialsLoading()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 5kB', 'CloudTranslation bridge credentials loading memory');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationBridge->getCredentials();
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testTranslationClientIsReturned()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 600kB', 'CloudTranslation client creation memory');
        $configuration->assert('main.network_in == 0B', 'CloudTranslation client creation network in');
        $configuration->assert('main.network_out == 0B', 'CloudTranslation client creation network out');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationBridge->createConnexion();
        });
    }
}
