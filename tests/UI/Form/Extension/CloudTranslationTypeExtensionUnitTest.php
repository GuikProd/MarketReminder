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

namespace App\Tests\UI\Form\Extension;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Form\Extension\CloudTranslationTypeExtension;
use App\UI\Form\Extension\Interfaces\CloudTranslationTypeExtensionInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\AbstractTypeExtension;

/**
 * Class CloudTranslationTypeExtensionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationTypeExtensionUnitTest extends TestCase
{
    /**
     * @var CloudTranslationRepositoryInterface|null
     */
    private $cloudTranslationRepository = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
    }

    public function testItImplementsAndExtends()
    {
        $extension = new CloudTranslationTypeExtension($this->cloudTranslationRepository);

        static::assertInstanceOf(
            AbstractTypeExtension::class,
            $extension
        );
        static::assertInstanceOf(
            CloudTranslationTypeExtensionInterface::class,
            $extension
        );
    }

    public function testItPrepareTheView()
    {
        $extension = new CloudTranslationTypeExtension($this->cloudTranslationRepository);
    }
}
