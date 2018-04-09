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

namespace App\Application\CacheWarmer;

use App\Application\CacheWarmer\Interfaces\TranslationCacheWarmerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Class TranslationCacheWarmer.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TranslationCacheWarmer extends CacheWarmer implements TranslationCacheWarmerInterface, CacheWarmerInterface
{
    /**
     * @var string
     */
    private $translationsFolder;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $translationsFolder)
    {
        $this->translationsFolder = $translationsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return true;
    }
}
