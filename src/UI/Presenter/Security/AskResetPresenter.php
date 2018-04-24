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

namespace App\UI\Presenter\Security;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Security\Interfaces\AskResetPasswordPresenterInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AskResetPresenter.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPresenter implements AskResetPasswordPresenterInterface, PresenterInterface
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
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'form' => FormView::class,
            'card_header' => '',
            'card_button'=> '',
            'page_title' => ''
        ]);

        $resolver->setAllowedTypes('form', FormView::class);
        $resolver->setAllowedTypes('card_header', 'string');
        $resolver->setAllowedTypes('card_button', 'string');
        $resolver->setAllowedTypes('page_title', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function getViewOptions(): array
    {
        return $this->viewOptions;
    }
}
