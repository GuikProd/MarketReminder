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

namespace App\UI\Form\Extension;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Form\Extension\Interfaces\CloudTranslationTypeExtensionInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CloudTranslationTypeExtension.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationTypeExtension extends AbstractTypeExtension implements CloudTranslationTypeExtensionInterface
{
    const TO_TRANSLATE_KEYS = ['label', 'help'];

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudTranslationRepositoryInterface $cloudTranslationRepository)
    {
        $this->cloudTranslationRepository = $cloudTranslationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['_locale']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        isset($options['_locale']) ? $view->vars['_locale'] = $options['_locale'] : $view->vars['_locale'] = '';

        $newVariables = [];

        foreach ($view->vars as $key => $value) {
            if (\in_array($key, self::TO_TRANSLATE_KEYS) && (!\is_null($value) && "" !== $value)) {
                try {
                    $newVariables[$key] = $this->cloudTranslationRepository->getSingleEntry(
                        'form.' . $view->vars['_locale'] . '.yaml',
                        $view->vars['_locale'],
                        $value
                    )->getValue();
                } catch (InvalidArgumentException $e) {
                    $newVariables[$key] = sprintf($e->getMessage());
                }
            }
        }

        $view->vars = array_replace($view->vars, $newVariables);
    }
}
