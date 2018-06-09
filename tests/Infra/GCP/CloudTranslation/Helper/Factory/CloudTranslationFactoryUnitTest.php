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

namespace App\Tests\Infra\GCP\CloudTranslation\Helper\Factory;

use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationInterface;
use App\Infra\GCP\CloudTranslation\Helper\Factory\CloudTranslationFactory;
use App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces\CloudTranslationFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationFactoryUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationFactoryUnitTest extends TestCase
{
    public function testItImplements()
    {
        $cloudTranslationFactory = new CloudTranslationFactory();

        static::assertInstanceOf(
            CloudTranslationFactoryInterface::class,
            $cloudTranslationFactory
        );
    }

    public function testItBuildTranslation()
    {
        $cloudTranslationFactory = new CloudTranslationFactory();

        $entry = $cloudTranslationFactory->buildCloudTranslation('', '');

        static::assertInstanceOf(
            CloudTranslationInterface::class,
            $entry
        );
    }

    public function testItBuildCloudTranslationItem()
    {
        $cloudTranslationFactory = new CloudTranslationFactory();

        $cloudTranslationFactory->buildCloudTranslationItem('', '', '', '');

        static::assertGreaterThan(0, $cloudTranslationFactory->getCloudTranslationItem());
    }
}
