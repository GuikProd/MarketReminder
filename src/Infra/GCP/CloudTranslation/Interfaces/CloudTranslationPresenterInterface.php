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

namespace App\Infra\GCP\CloudTranslation\Interfaces;

/**
 * Interface CloudTranslationPresenterInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudTranslationPresenterInterface
{
    /**
     * PresenterInterface constructor.
     *
     * @param CloudTranslationRepositoryInterface $redisTranslationRepository
     */
    public function __construct(CloudTranslationRepositoryInterface $redisTranslationRepository);

    /**
     * @param array $viewOptions
     *
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \LogicException
     *
     * @return array The new view options translated.
     */
    public function prepareTranslations(array $viewOptions): array;
}
