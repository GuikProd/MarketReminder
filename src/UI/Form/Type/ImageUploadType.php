<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\UI\Form\Type;

use App\Domain\UseCase\UserRegistration\DTO\ImageRegistrationDTO;
use App\Domain\UseCase\UserRegistration\DTO\Interfaces\ImageRegistrationDTOInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImageUploadType
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadType extends AbstractType
{
    private $profile

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alt', TextType::class)
            ->add('file', FileType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['imageUpload'],
            'data_class' => ImageRegistrationDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new ImageRegistrationDTO(
                    $form->get('alt')->getData(),
                    $form->getData()->publicUrl
                );
            }
        ]);
    }
}
