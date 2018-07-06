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

use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;

/**
 * Interface TranslationWarmerCommandInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface TranslationWarmerCommandInterface
{
    /**
     * TranslationWarmerCommandInterface constructor.
     *
     * @param string $acceptedChannels
     * @param string $acceptedLocales
     * @param CloudTranslationWarmerInterface $cloudTranslationWarmer
     *
     * {@internal this command SHOULD call the @see Command constructor}
     */
    public function __construct(
        string $acceptedChannels,
        string $acceptedLocales,
        CloudTranslationWarmerInterface $cloudTranslationWarmer
    );
}
