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

namespace App\UI\Presenter;

use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractPresenter.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class AbstractPresenter implements PresenterInterface
{
    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var array
     */
    private $viewOptions;

    /**
     * {@inheritdoc}
     */
    public function __construct(RedisTranslationRepositoryInterface $redisTranslationRepository)
    {
        $this->redisTranslationRepository = $redisTranslationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareOptions(array $viewOptions = array()): void
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        try {
            $translatedViewOptions = $this->prepareTranslations($viewOptions);
        } catch (\Psr\Cache\InvalidArgumentException $exception) {
            sprintf($exception->getMessage());
        }

        $this->viewOptions = $resolver->resolve($translatedViewOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function prepareTranslations(array $viewOptions): array
    {
        $translatedContent = [];

        foreach ($viewOptions['page'] as $item =>  $value) {

            if (!$this->redisTranslationRepository->getSingleEntry(
                $value['channel'].'.'.$value['_locale'].'.yaml',
                $value['_locale'],
                $value['title']
            )) {
                throw new \InvalidArgumentException(
                    sprintf('No entry can be found, please warm the translations !')
                );
            }

            $translatedContent[] = $this->redisTranslationRepository->getSingleEntry(
                $value['channel'].'.'.$value['_locale'].'.yaml',
                $value['_locale'],
                $value['title']
            );

            foreach ($translatedContent as $key => $translation) {
                if (!array_key_exists('value', $value)) {
                    throw new \LogicException(
                        sprintf('The option passed should contain a value key !')
                    );
                }

                $value['value'] = $translation->getValue();
            }

            $viewOptions['page'][$item] = $value;
        }

        return $viewOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getPage(): array
    {
        return $this->viewOptions['page'];
    }

    /**
     * {@inheritdoc}
     */
    public function getViewOptions(): array
    {
        return $this->viewOptions;
    }
}
