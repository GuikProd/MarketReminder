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

namespace App\UI\Presenter\User;

use App\Domain\Models\Interfaces\UserInterface;
use App\UI\Presenter\AbstractPresenter;
use App\UI\Presenter\User\Interfaces\UserEmailPresenterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserEmailPresenter.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserEmailPresenter extends AbstractPresenter implements UserEmailPresenterInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'email' => [
                'content' => null,
                'link' => [
                    'text' => null
                ],
                'subject' => null,
                'to' => null
            ],
            'user' => null
        ]);

        $resolver->setAllowedTypes('email', 'array');
        $resolver->setAllowedTypes('user', UserInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): array
    {
        return $this->getViewOptions()['email'];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): array
    {
        return $this->getViewOptions()['user'];
    }
}
