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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockItemCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockItemCreationDTO;
use App\UI\Form\Subscriber\Interfaces\StockItemCreationSubscriberInterface;
use App\UI\Form\Type\Stock\Interfaces\StockItemCreationTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StockItemCreationType.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemCreationType extends AbstractType implements StockItemCreationTypeInterface
{
    /**
     * @var StockItemCreationSubscriberInterface
     */
    private $stockItemSubscriber;

    /**
     * {@inheritdoc}
     */
    public function __construct(StockItemCreationSubscriberInterface $stockItemSubscriber)
    {
        $this->stockItemSubscriber = $stockItemSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'stock.creation.item_on.text' => 'on',
                    'stock.creation.item_off.text' => 'off',
                ],
                'help' => 'stock.creation.item_help'
            ])
            ->add('quantity', IntegerType::class)
            ->add('withoutTaxesPrice', MoneyType::class)
            ->add('withTaxesPrice', MoneyType::class)
            ->add('type', TextType::class)
        ;

        $builder->get('type')->addEventSubscriber($this->stockItemSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StockItemCreationDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new StockItemCreationDTO(
                    $form->get('name')->getData(),
                    $form->get('status')->getData(),
                    $form->get('quantity')->getData(),
                    $form->get('withoutTaxesPrice')->getData(),
                    $form->get('withTaxesPrice')->getData(),
                    $form->get('type')->getData(),
                    $form->get('limitUsageDate') ? $form->get('limitUsageDate')->getData() :  null,
                    $form->get('limitConsumptionDate') ? $form->get('limitConsumptionDate')->getData() : null,
                    $form->get('limitOptimalUsageDate') ? $form->get('limitOptimalUsageDate')->getData() : null
                );
            }
        ]);
    }
}
