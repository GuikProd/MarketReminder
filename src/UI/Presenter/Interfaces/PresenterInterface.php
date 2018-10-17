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

use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface PresenterInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface PresenterInterface
{
    /**
     * PresenterInterface constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator);

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
     * ],
     * 'content' => []
     * ```
     *
     * In order to execute properly, the 'value' key should be blank,
     * once the presenter is build, the CloudTranslationRepository
     * is used in order to retrieve the translated text.
     *
     * @internal The default structure is free as long as the
     *           channel and key keys are defined inside the page array.
     *
     * @param Options $resolver @see OptionsResolver
     *
     * @throws \LogicException
     */
    public function configureOptions(Options $resolver): void;

    /**
     * @param array $content
     *
     * @return array
     */
    public function prepareContent(array $content = []): array ;

    /**
     * @param array $values
     *
     * @return void
     */
    public function prepareForm(array $values = []): void;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return FormView|null The FormView which contain the translated values.
     */
    public function getForm(): ?FormView;

    /**
     * @return array
     */
    public function getContent(): array;

    /**
     * @return array
     */
    public function getPage(): array;

    /**
     * @return array
     */
    public function getViewOptions(): array;
}
