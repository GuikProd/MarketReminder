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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'help' => 'stock_creation.title'
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'stock.creation_on.text' => 'on',
                    'stock.creation_off.text' => 'off'
                ],
                'help' => 'stock_creation.choices'
            ])
            ->add('tags', StockTagsType::class, [
                'help' => 'stock.creation.tags'
            ])
            ->add('stockItems', CollectionType::class, [
                'entry_type' => StockItemCreationType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
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
                    $form->get('tags')->getData()->tags,
                    $form->get('stockItems')->getData()
                );
            }
        ]);
    }
}
