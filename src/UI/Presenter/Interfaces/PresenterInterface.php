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

namespace App\UI\Presenter\Interfaces;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface PresenterInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface PresenterInterface
{
    /**
     * @param array $viewOptions
     */
    public function prepareOptions(array $viewOptions = array()): void;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void;

    /**
     * @return array
     */
    public function getPage(): array;

    /**
     * @return array
     */
    public function getViewOptions(): array;
}
