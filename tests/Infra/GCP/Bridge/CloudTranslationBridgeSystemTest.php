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
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationBridgeSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationBridgeSystemTest extends KernelTestCase
{
    use TestCaseTrait;

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

        $this->translationCredentialsFileName = static::$kernel->getContainer()->getParameter('cloud.translation_credentials.filename');
        $this->translationCredentialsFolder = static::$kernel->getContainer()->getParameter('cloud.translation_credentials');

        $this->cloudTranslationBridge = new CloudTranslationBridge(
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
        $configuration->assert('main.peak_memory < 5kB', 'Cloud Translation bridge credentials loading memory');

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
        $configuration->assert('main.peak_memory < 750kB', 'Cloud Translation bridge credentials loading memory');
        $configuration->assert('main.network_in == 0B', 'Cloud Translation bridge translation client network in');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationBridge->getTranslateClient();
        });
    }
}
