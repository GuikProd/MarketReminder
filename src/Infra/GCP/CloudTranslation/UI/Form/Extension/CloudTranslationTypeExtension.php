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

namespace App\Infra\GCP\CloudTranslation\UI\Form\Extension;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\UI\Form\Extension\Interfaces\CloudTranslationTypeExtensionInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CloudTranslationTypeExtension.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationTypeExtension extends AbstractTypeExtension implements CloudTranslationTypeExtensionInterface
{
    const REQUIRED_OPTIONS = ['label', 'help'];

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudTranslationRepositoryInterface $cloudTranslationRepository,
        RequestStack $requestStack
    ) {
        $this->cloudTranslationRepository = $cloudTranslationRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();

        $viewOptions = $form->getConfig()->getOptions();

        foreach ($viewOptions as $option => $optionValue) {
            if (\in_array($option, self::REQUIRED_OPTIONS) && null !== $optionValue) {
                $viewOptions[$option] = $this->cloudTranslationRepository->getSingleEntry('form.'.$locale.'.yaml', $locale, $optionValue)->getValue();
            }
        }

        $view->vars = array_replace($view->vars, $viewOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('translate');
    }
}
