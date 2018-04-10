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

namespace App\Tests\Application\CacheWarmer;

use App\Application\CacheWarmer\TranslationCacheWarmer;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Class TranslationCacheWarmerTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TranslationCacheWarmerTest extends KernelTestCase
{
    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var string
     */
    private $translationFolder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->cloudTranslationWarmer = $this->createMock(CloudTranslationWarmerInterface::class);
        $this->translationFolder = static::$kernel->getContainer()->getParameter('translator.default_path');
    }

    public function testItImplements()
    {
        $translatorCacheWarmer = new TranslationCacheWarmer(
            $this->cloudTranslationWarmer,
            $this->translationFolder
        );

        static::assertInstanceOf(
            CacheWarmerInterface::class,
            $translatorCacheWarmer
        );

        static::assertInstanceOf(
            CacheWarmer::class,
            $translatorCacheWarmer
        );
    }

    public function testItsOptional()
    {
        $translatorCacheWarmer = new TranslationCacheWarmer(
            $this->cloudTranslationWarmer,
            $this->translationFolder
        );

        static::assertTrue($translatorCacheWarmer->isOptional());
    }
}
