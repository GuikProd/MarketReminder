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

namespace App\Infra\GCP\CloudTranslation\UI\Interfaces;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;

/**
 * Interface CloudTranslationPresenterInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
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

    /**
     * @param array $viewVariables
     * @param string $locale
     *
     * @return void
     */
    public function translateFormViewVariables(array $viewVariables, string $locale): void;
}
