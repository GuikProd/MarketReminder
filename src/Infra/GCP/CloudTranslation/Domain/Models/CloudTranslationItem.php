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

namespace App\Infra\GCP\CloudTranslation\Domain\Models;

use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationItemInterface;

/**
 * Class CloudTranslationItem.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationItem implements CloudTranslationItemInterface
{
    const AUTHORIZED_OPTIONS = ['_locale', 'channel', 'tag', 'key', 'value'];

    /**
     * @var array
     */
    private $options = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options)
    {
        foreach (array_values($options) as $value) {
            if (!\is_string($value)) {
                throw new \InvalidArgumentException(
                    sprintf('A Translation should only store strings !')
                );
            }
        }

        foreach (array_keys($options) as $key) {
            if (!in_array($key, self::AUTHORIZED_OPTIONS)) {
                throw new \InvalidArgumentException(
                    sprintf('This key must be allowed, please check the default requirements !')
                );
            }
        }

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): string
    {
        return $this->options['_locale'];
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel(): string
    {
        return $this->options['channel'];
    }

    /**
     * {@inheritdoc}
     */
    public function getTag(): string
    {
        return $this->options['tag'];
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return $this->options['key'];
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        return $this->options['value'];
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
