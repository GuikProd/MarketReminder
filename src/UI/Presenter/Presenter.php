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

use App\Domain\Models\Interfaces\UserInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationPresenterInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Presenter.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Presenter implements PresenterInterface, CloudTranslationPresenterInterface
{
    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var array
     */
    private $viewOptions;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudTranslationRepositoryInterface $redisTranslationRepository)
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
        foreach ($viewOptions['page'] as $item =>  $value) {
            if (!$redisTranslation = $this->redisTranslationRepository->getSingleEntry(
                $value['channel'].'.'.$viewOptions['_locale'].'.yaml',
                $viewOptions['_locale'],
                $value['key']
            )) {
                $value['value'] = $value['key'];
            }

            if (!array_key_exists('value', $value) && $redisTranslation->getKey() == $value['key']) {
                $value['value'] = $redisTranslation->getValue();
            }

            $viewOptions['page'][$item] = $value;
        }

        return $viewOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(Options $resolver): void
    {
        $resolver->setDefaults([
            '_locale' => '',
            'form' => null,
            'user' => null,
            'page' => []
        ]);

        $resolver->setAllowedTypes('_locale', 'string');
        $resolver->setAllowedTypes('form', array('null', FormInterface::class));
        $resolver->setAllowedTypes('page', 'array');
        $resolver->setAllowedTypes('user', array('null', UserInterface::class));
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
