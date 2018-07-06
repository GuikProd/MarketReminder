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

namespace App\Tests\Infra\GCP\CloudTranslation\Templating\Twig\Extension;

use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Templating\Twig\Extension\CloudTranslationExtension;
use App\Infra\GCP\CloudTranslation\Templating\Twig\Extension\Interfaces\CloudTranslationExtensionInterface;
use PHPUnit\Framework\TestCase;
use Twig\Extension\AbstractExtension;

/**
 * Class CloudTranslationExtensionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationExtensionUnitTest extends TestCase
{
    /**
     * @var CloudTranslationItemInterface
     */
    private $cloudTranslationItem;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationItem = $this->createMock(CloudTranslationItemInterface::class);
        $this->cloudTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);

        $this->cloudTranslationRepository->method('getSingleEntry')->willReturn($this->cloudTranslationItem);
    }

    public function testItImplements()
    {
        $extension = new CloudTranslationExtension($this->cloudTranslationRepository);

        static::assertInstanceOf(AbstractExtension::class, $extension);
        static::assertInstanceOf(CloudTranslationExtensionInterface::class, $extension);
    }

    /**
     * @dataProvider provideData
     *
     * @param string $key
     * @param string $value
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItTranslate(string $key, string $value)
    {
        $this->cloudTranslationItem->method('getValue')->willReturn($value);

        $extension = new CloudTranslationExtension($this->cloudTranslationRepository);

        static::assertSame($value, $extension->cloudTranslate($key));
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield array('home.text', 'Hello World !');
        yield array('home.welcome', 'Hello World from World !');
    }
}
