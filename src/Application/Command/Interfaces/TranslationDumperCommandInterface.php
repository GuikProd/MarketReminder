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

namespace App\Application\Command\Interfaces;

use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationTranslatorInterface;

/**
 * Interface TranslationDumperCommandInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface TranslationDumperCommandInterface
{
    /**
     * TranslationDumperCommandInterface constructor.
     *
     * @param string $acceptedChannels
     * @param string $acceptedLocales
     * @param CloudTranslationTranslatorInterface $translator
     */
    public function __construct(
        string $acceptedChannels,
        string $acceptedLocales,
        CloudTranslationTranslatorInterface $translator
    );
}
