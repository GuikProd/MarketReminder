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
     * @var array
     */
    private $viewOptions;

    /**
     * {@inheritdoc}
     */
    public function prepareOptions(array $viewOptions = array()): void
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->viewOptions = $resolver->resolve($viewOptions);
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
