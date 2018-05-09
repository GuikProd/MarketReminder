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

namespace App\Infra\Redis\Translation\Interfaces;

use Symfony\Component\OptionsResolver\Options;

/**
 * Interface RedisTranslationInterface.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RedisTranslationInterface
{
    /**
     * RedisTranslationInterface constructor.
     *
     * @param array $options
     */
    public function __construct(array $options);

    /**
     * @param Options $resolver
     */
    public function configureOptions(Options $resolver): void;

    /**
     * @return string
     */
    public function getLocale(): string;

    /**
     * @return string
     */
    public function getChannel(): string;

    /**
     * @return string
     */
    public function getTag(): string;

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @return string
     */
    public function getValue(): string;
}
