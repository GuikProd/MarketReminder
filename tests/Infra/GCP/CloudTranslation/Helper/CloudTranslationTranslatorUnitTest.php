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

namespace App\Tests\Infra\GCP\CloudTranslation\Helper;

use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationTranslator;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationTranslatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationTranslatorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationTranslatorUnitTest extends TestCase
{
    public function testItImplements()
    {
        $cloudTranslationTranslator = new CloudTranslationTranslator();

        static::assertInstanceOf(
            CloudTranslationTranslatorInterface::class,
            $cloudTranslationTranslator
        );
    }

    public function testItTranslate()
    {
        $cloudTranslationTranslator = new CloudTranslationTranslator();
    }
}
