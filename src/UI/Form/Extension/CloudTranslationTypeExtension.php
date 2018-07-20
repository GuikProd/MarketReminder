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
use Symfony\Component\HttpFoundation\RequestStack;

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
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();

        $newVariables = [];

        foreach ($view->vars as $key => $value) {
            if (\in_array($key, self::TO_TRANSLATE_KEYS) && (!\is_null($value) && "" !== $value)) {
                try {
                    $newVariables[$key] = $this->cloudTranslationRepository->getSingleEntry(
                        'form.' . $locale . '.yaml',
                        $locale,
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
