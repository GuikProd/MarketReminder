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

namespace App\UI\Presenter\Interfaces;

use Symfony\Component\OptionsResolver\Options;

/**
 * Interface PresenterInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface PresenterInterface
{
    /**
     * @param array $viewOptions
     */
    public function prepareOptions(array $viewOptions = array()): void;

    /**
     * Allow to define the default structure of every presenter.
     *
     * For simplicity purpose, here's a default structure :
     *
     * ```php
     * '_locale' => '',
     * 'form' => FormInterface::class,
     * 'user' => UserInterface::class,
     * 'page' => [
     *     'button' => [
     *         'channel' => 'messages',
     *         'key' => 'home.text',
     *         'class' => '',
     *         'id' => ''
     *     ]
     * ]
     * ```
     *
     * In order to execute properly, the 'value' key should be blank,
     * once the presenter is build, the CloudTranslationRepository
     * is used in order to retrieve the translated text.
     *
     * @internal The default structure is free as long as the
     *           channel and key keys are defined.
     *
     * @param Options $resolver @see OptionsResolver
     *
     * @throws \LogicException
     */
    public function configureOptions(Options $resolver): void;

    /**
     * @return array
     */
    public function getPage(): array;

    /**
     * @return array
     */
    public function getViewOptions(): array;
}
