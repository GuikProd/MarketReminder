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

namespace App\Application\Command\Interfaces;

use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;

/**
 * Interface TranslationWarmerCommandInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface TranslationWarmerCommandInterface
{
    /**
     * TranslationWarmerCommandInterface constructor.
     *
     * @param string                              $acceptedLocales
     * @param CloudTranslationWarmerInterface     $cloudTranslationWarmer
     * @param RedisTranslationRepositoryInterface $redisTranslationRepository
     * @param RedisTranslationWriterInterface     $redisTranslationWriter
     * @param string                              $translationsFolder
     *
     * {@internal} this command SHOULD call the @see Command constructor
     */
    public function __construct(
        string $acceptedLocales,
        CloudTranslationWarmerInterface $cloudTranslationWarmer,
        RedisTranslationRepositoryInterface $redisTranslationRepository,
        RedisTranslationWriterInterface $redisTranslationWriter,
        string $translationsFolder
    );
}
