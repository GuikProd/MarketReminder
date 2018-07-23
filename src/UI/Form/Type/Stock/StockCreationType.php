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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockCreationDTO;
use App\UI\Form\DataTransformer\Interfaces\StockCreationTagsTransformerInterface;
use App\UI\Form\Type\Stock\Interfaces\StockCreationTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StockCreationType.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationType extends AbstractType implements StockCreationTypeInterface
{
    /**
     * @var StockCreationTagsTransformerInterface
     */
    private $stockTagsTransformer;

    /**
     * {@inheritdoc}
     */
    public function __construct(StockCreationTagsTransformerInterface $stockTagsTransformer)
    {
        $this->stockTagsTransformer = $stockTagsTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'stock.creation_title',
                'help' => 'stock.creation_title',
                'attr' => [
                    'minLength' => 5,
                    'maxLength' => 50
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'stock.creation_choices',
                'choices' => [
                    'stock.creation_choices.on_text' => 'on',
                    'stock.creation_choices.off_text' => 'off'
                ],
                'help' => 'stock.creation_choices',
                'choice_attr' => [
                    'help' => 'stock.creation_choices.help'
                ]
            ])
            ->add('tags', TextType::class, [
                'label' => 'stock.creation_tags',
                'help' => 'stock.creation_tags',
                'required' => false
            ])
            ->add('stockItems', CollectionType::class, [
                'entry_type' => StockItemCreationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'help' => 'stock.creation_items',
            ])
        ;

        $builder->get('tags')->addViewTransformer($this->stockTagsTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StockCreationDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new StockCreationDTO(
                    $form->get('title')->getData(),
                    $form->get('status')->getData(),
                    $form->get('tags')->getData(),
                    $form->get('stockItems')->getData()
                );
            },
            'validation_groups' => ['stock_creation']
        ]);
    }
}
