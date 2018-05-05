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

namespace App\Infra\Redis\Translation;

use App\Infra\Redis\Translation\Interfaces\RedisTranslationInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RedisTranslation.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RedisTranslation implements RedisTranslationInterface
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
            'channel' => null,
            'tag' => null,
            'key' => null,
            'value' => null
        ]);

        $resolver->setAllowedTypes('channel', 'string');
        $resolver->setAllowedTypes('tag', 'string');
        $resolver->setAllowedTypes('key', 'string');
        $resolver->setAllowedTypes('value', 'string');
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
