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

namespace App\Infra\GCP\CloudTranslation\Bridge\Interfaces;

use Google\Cloud\Translate\TranslateClient;

/**
 * Interface CloudTranslationBridgeInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationBridgeInterface
{
    /**
     * CloudTranslationBridgeInterface constructor.
     *
     * @param string $translationCredentialsFileName
     * @param string $translationCredentialsFolder
     */
    public function __construct(
        string $translationCredentialsFileName,
        string $translationCredentialsFolder
    );

    /**
     * @return TranslateClient
     */
    public function getTranslateClient(): TranslateClient;
}
