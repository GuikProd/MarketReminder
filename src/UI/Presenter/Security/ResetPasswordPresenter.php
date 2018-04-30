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
use App\UI\Presenter\Security\Interfaces\ResetPasswordPresenterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResetPasswordPresenter.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordPresenter extends AbstractPresenter implements ResetPasswordPresenterInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'form' => null,
            'notification' => [
                'content' => null,
                'title' => null
            ],
            'page' => [
                'title' => null,
                'button' => [
                    'content' => null
                ]
            ]
        ]);

        $resolver->setAllowedTypes('form', array('null', FormInterface::class));
        $resolver->setAllowedTypes('notification', 'array');
        $resolver->setAllowedTypes('page', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(): ?FormView
    {
        return $this->getViewOptions()['form']->createView() ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotificationMessage(): array
    {
        return $this->getViewOptions()['notification'];
    }
}
