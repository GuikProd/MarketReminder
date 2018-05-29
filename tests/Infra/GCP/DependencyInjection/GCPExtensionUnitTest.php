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

namespace App\Tests\Application\DependencyInjection;

use App\Infra\GCP\DependencyInjection\GCPExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Class GCPExtensionUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class GCPExtensionUnitTest extends TestCase
{
    public function testItExtends()
    {
        $extension = new GCPExtension();

        static::assertInstanceOf(Extension::class, $extension);
    }
}
