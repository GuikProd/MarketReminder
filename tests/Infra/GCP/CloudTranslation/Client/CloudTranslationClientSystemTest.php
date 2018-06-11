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

namespace App\Tests\Infra\GCP\CloudTranslation;

use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\Client\CloudTranslationClient;
use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationClientSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationClientSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

    /**
     * @var CloudTranslationClientInterface
     */
    private $cloudTranslationHelper;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->cloudTranslationBridge = new CloudTranslationBridge(
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials.filename'),
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials')
        );

        $this->cloudTranslationHelper = new CloudTranslationClient($this->cloudTranslationBridge);
    }

    public function testItTranslateASingleElement()
    {
        $configuration = new Configuration();
        $configuration->assert('metrics.http.requests.count <= 2', 'CloudTranslationClient single translation HTTP requests');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationHelper->translateSingleEntry('Bien le bonjour !', 'en');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItTranslateAnArrayOfElements()
    {
        $configuration = new Configuration();
        $configuration->assert(
            'main.peak_memory < 170kB',
            'CloudTranslationClient multiples translations warm memory usage'
        );
        $configuration->assert(
            'metrics.http.requests.count <= 2',
            'CloudTranslationClient multiples translation HTTP requests'
        );
        $configuration->assert(
            'main.network_in < 1.55kB',
            'CloudTranslationClient multiples translation network in'
        );
        $configuration->assert(
            'main.network_out < 785B',
            'CloudTranslationClient multiples translation network out'
        );

        $this->assertBlackfire($configuration, function() {
            $this->cloudTranslationHelper->translateArray([
                'Bien le bonjour',
                'Petit test'
            ], 'en');
        });
    }
}
