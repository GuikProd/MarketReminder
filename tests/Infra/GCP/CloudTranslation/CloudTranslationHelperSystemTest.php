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

use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\CloudTranslationHelper;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationHelperInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationHelperSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationHelperSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

    /**
     * @var CloudTranslationHelperInterface
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

        $this->cloudTranslationHelper = new CloudTranslationHelper($this->cloudTranslationBridge);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItTranslateASingleElement()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1.45MB', 'CloudTranslationHelper single translation warm memory usage');
        $configuration->assert('metrics.http.requests.count <= 2', 'CloudTranslationHelper single translation HTTP requests');
        $configuration->assert('main.network_in < 1.55kB', 'CloudTranslationHelper single translation network in');
        $configuration->assert('main.network_out < 780B', 'CloudTranslationHelper single translation network out');

        $this->assertBlackfire($configuration, function() {
            $this->cloudTranslationHelper->warmTranslation('Bien le bonjour', 'en');
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
        $configuration->assert('main.peak_memory < 170kB', 'CloudTranslationHelper multiples translations warm memory usage');
        $configuration->assert('metrics.http.requests.count <= 2', 'CloudTranslationHelper multiples translation HTTP requests');
        $configuration->assert('main.network_in < 1.55kB', 'CloudTranslationHelper multiples translation network in');
        $configuration->assert('main.network_out < 785B', 'CloudTranslationHelper multiples translation network out');

        $this->assertBlackfire($configuration, function() {
            $this->cloudTranslationHelper->warmArrayTranslation([
                'Bien le bonjour',
                'Petit test'
            ], 'en');
        });
    }
}
