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

use App\UI\Presenter\AbstractPresenter;
use App\UI\Presenter\Security\Interfaces\AskResetPasswordPresenterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AskResetPasswordPresenter.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordPresenter extends AbstractPresenter implements AskResetPasswordPresenterInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'form' => FormInterface::class ?? null,
            'card' => [
                'card_header' => '',
                'card_button'=> '',
            ],
            'page' => [
                'title' => ''
            ],
        ]);

        $resolver->setAllowedTypes('card', 'array');
        $resolver->setAllowedTypes('page', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getCard(): array
    {
        return $this->getViewOptions()['card'];
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(): ?FormView
    {
        return $this->getViewOptions()['form']->createView() ?? null;
    }
}
