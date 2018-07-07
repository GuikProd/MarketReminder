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

namespace App\UI\Form\Type\Stock;

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockTagsDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockTagsDTO;
use App\UI\Form\DataTransformer\Interfaces\StockCreationTagsTransformerInterface;
use App\UI\Form\Type\Stock\Interfaces\StockTagsTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StockTagsType.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockTagsType extends AbstractType implements StockTagsTypeInterface
{
    /**
     * @var StockCreationTagsTransformerInterface
     */
    private $tagsTransformer;

    /**
     * {@inheritdoc}
     */
    public function __construct(StockCreationTagsTransformerInterface $tagsTransformer)
    {
        $this->tagsTransformer = $tagsTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tags', TextType::class);

        $builder->get('tags')->addViewTransformer($this->tagsTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StockTagsDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new StockTagsDTO(
                    $form->get('tags')->getData()
                );
            }
        ]);
    }
}
