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

namespace App\Tests\Infra\GCP\CloudTranslation\UI\Form\Extension;

use App\Infra\GCP\CloudTranslation\UI\Form\Extension\CloudTranslationTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class CloudTranslationTypeExtensionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationTypeExtensionUnitTest extends TestCase
{
    public function testItImplements()
    {
        $extension = new CloudTranslationTypeExtension();

        static::assertSame(FormType::class, $extension->getExtendedType());
    }
}
