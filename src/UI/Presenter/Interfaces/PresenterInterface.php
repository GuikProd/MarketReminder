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

namespace App\UI\Presenter\Interfaces;

use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface PresenterInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface PresenterInterface
{
    /**
     * PresenterInterface constructor.
     *
     * @param RedisTranslationRepositoryInterface $redisTranslationRepository
     */
    public function __construct(RedisTranslationRepositoryInterface $redisTranslationRepository);

    /**
     * @param array $viewOptions
     */
    public function prepareOptions(array $viewOptions = array()): void;

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \LogicException
     */
    public function configureOptions(OptionsResolver $resolver): void;

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
     * @return array
     */
    public function getPage(): array;

    /**
     * @return array
     */
    public function getViewOptions(): array;
}
