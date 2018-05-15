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

namespace App\UI\Presenter\Core;

use App\UI\Presenter\AbstractPresenter;
use App\UI\Presenter\Core\Interfaces\HomePresenterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HomePresenter.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomePresenter extends AbstractPresenter implements HomePresenterInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'page' => []
        ]);

        $resolver->setAllowedTypes('page', 'array');
    }
}
