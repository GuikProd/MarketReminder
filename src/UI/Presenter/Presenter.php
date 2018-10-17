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
use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Presenter.
 *
 * @package App\UI\Presenter
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class Presenter implements PresenterInterface
{
    const FORM_ALLOWED_VARS = ['label', 'help'];
    const PAGE_ALLOWED_VARS = ['content', 'page'];

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $viewOptions;

    /**
     * {@inheritdoc}
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareOptions(array $viewOptions = array()): void
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $content = $this->prepareContent($viewOptions);

        $this->viewOptions = $resolver->resolve($content);
    }

    /**
     * @inheritdoc
     */
    public function prepareContent(array $content = []): array
    {
        for ($i = 0; $i < \count(self::PAGE_ALLOWED_VARS); $i++) {

            foreach ($content[self::PAGE_ALLOWED_VARS[$i]] as $item =>  $value) {

                if (!\is_array($value) || \count($value) == 0) {
                    continue;
                }

                if (!$translation = $this->translator->trans($value['key'], [], $value['channel'])) {
                    $value['value'] = $value['key'];
                }

                $value['value'] = $translation;

                $content[self::PAGE_ALLOWED_VARS[$i]][$item] = $value;
            }
        }

        return $content;
    }

    /**
     * @inheritdoc
     */
    public function prepareForm(array $values = []): void
    {
        foreach ($values as $key => $entry) {
            foreach ($entry->vars as $k => $v) {
                if (!\in_array($k, self::FORM_ALLOWED_VARS) || \is_null($v)) {
                    continue;
                }

                $entry->vars[$k] = $this->translator->trans($v, [], 'form');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(Options $resolver): void
    {
        $resolver->setDefaults([
            '_locale' => '',
            'body' => null,
            'content' => null,
            'form' => null,
            'user' => null,
            'page' => [],
            'data' => []
        ]);

        $resolver->setAllowedTypes('_locale', 'string');
        $resolver->setAllowedTypes('body', array('string', 'null'));
        $resolver->setAllowedTypes('content', array('array', 'null'));
        $resolver->setAllowedTypes('data', 'array');
        $resolver->setAllowedTypes('form', array('null', FormInterface::class));
        $resolver->setAllowedTypes('page', 'array');
        $resolver->setAllowedTypes('user', array('array', 'null', UserInterface::class));
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
        $formView = $this->viewOptions['form']->createView() ?? null;

        if (!\is_null($formView)) {
            $this->prepareForm($formView->children);
        }

        return $formView;
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
