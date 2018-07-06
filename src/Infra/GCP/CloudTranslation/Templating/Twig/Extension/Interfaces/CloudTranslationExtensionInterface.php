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

namespace App\Infra\GCP\CloudTranslation\Templating\Twig\Extension\Interfaces;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;

/**
 * Interface CloudTranslationExtensionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationExtensionInterface
{
    /**
     * CloudTranslationExtensionInterface constructor.
     *
     * @param CloudTranslationRepositoryInterface $cloudTranslationRepository
     */
    public function __construct(CloudTranslationRepositoryInterface $cloudTranslationRepository);

    /**
     * Allow to find and return the "translated" version of the $key.
     *
     * @param string $key       The key used to find the translation.
     * @param string $filename  The name of the file stored in the cache layer.
     * @param string $locale    The locale used to find the correct file version.
     *
     * @throws \Psr\Cache\InvalidArgumentException @see CloudTranslationRepository::getSingleEntry
     *
     * @return string           The translated version of the key.
     */
    public function cloudTranslate(string $key, string $filename = 'messages', string $locale = 'fr'): string;
}
