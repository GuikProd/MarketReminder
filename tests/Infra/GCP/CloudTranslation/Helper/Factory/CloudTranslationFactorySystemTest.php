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

namespace App\Tests\Infra\GCP\CloudTranslation\Helper\Factory;

use App\Infra\GCP\CloudTranslation\Helper\Factory\CloudTranslationFactory;
use App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces\CloudTranslationFactoryInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationFactorySystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationFactorySystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationFactoryInterface
     */
    private $cloudTranslationFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationFactory = new CloudTranslationFactory();
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItBuildCloudTranslation()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 47kB', 'CloudTranslationFactory CloudTranslation memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationFactory->buildCloudTranslation('', '');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItBuildCloudTranslationItem()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 10kB', 'CloudTranslationFactory CloudTranslationItem memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationFactory->buildCloudTranslationItem('', '', '', '');
        });
    }
}
