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

namespace App\UI\Presenter;

use App\Domain\Models\Interfaces\UserInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\UI\Interfaces\CloudTranslationPresenterInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Presenter.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class Presenter implements PresenterInterface, CloudTranslationPresenterInterface
{
    const FORM_ALLOWED_VARS = ['label', 'help'];
    const PAGE_ALLOWED_VARS = ['content', 'page'];

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * @var array
     */
    private $viewOptions;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudTranslationRepositoryInterface $redisTranslationRepository)
    {
        $this->cloudTranslationRepository = $redisTranslationRepository;
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
        } catch (InvalidArgumentException $exception) {
            sprintf($exception->getMessage());
        }

        $this->viewOptions = $resolver->resolve($translatedViewOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function prepareTranslations(array $viewOptions): array
    {
        for ($i = 0; $i < \count(self::PAGE_ALLOWED_VARS); $i++) {

            foreach ($viewOptions[self::PAGE_ALLOWED_VARS[$i]] as $item =>  $value) {

                if (!\is_array($value) || \count($value) == 0) { continue; }

                if (!$redisTranslation = $this->cloudTranslationRepository->getSingleEntry(
                    $value['channel'].'.'.$viewOptions['_locale'].'.yaml',
                    $viewOptions['_locale'],
                    $value['key']
                )) {
                    $value['value'] = $value['key'];
                } elseif ($redisTranslation->getKey() == $value['key']) {
                    $value['value'] = $redisTranslation->getValue();
                }

                $viewOptions[self::PAGE_ALLOWED_VARS[$i]][$item] = $value;
            }
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
            'content' => [],
            'form' => null,
            'user' => null,
            'page' => [],
            'data' => []
        ]);

        $resolver->setAllowedTypes('_locale', 'string');
        $resolver->setAllowedTypes('content', 'array');
        $resolver->setAllowedTypes('data', 'array');
        $resolver->setAllowedTypes('form', array('null', FormInterface::class));
        $resolver->setAllowedTypes('page', 'array');
        $resolver->setAllowedTypes('user', array('null', UserInterface::class));
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->viewOptions['data'];
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(): array
    {
        return $this->viewOptions['content'];
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(): ?FormView
    {
        return $this->viewOptions['form']->createView() ?? null;
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
