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

namespace App\Infra\GCP\CloudTranslation;

use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationItemInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CloudTranslationItem.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationItem implements CloudTranslationItemInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options)
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(Options $resolver): void
    {
        $resolver->setDefaults([
            '_locale' => null,
            'channel' => null,
            'tag' => null,
            'key' => null,
            'value' => null
        ]);

        $resolver->setAllowedTypes('_locale', 'string');
        $resolver->setAllowedTypes('channel', 'string');
        $resolver->setAllowedTypes('tag', 'string');
        $resolver->setAllowedTypes('key', 'string');
        $resolver->setAllowedTypes('value', 'string');
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
