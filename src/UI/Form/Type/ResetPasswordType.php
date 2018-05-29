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

namespace App\UI\Form\Type;

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserNewPasswordDTOInterface;
use App\Domain\UseCase\UserResetPassword\DTO\UserNewPasswordDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResetPasswordType.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ResetPasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'password.not_match'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserNewPasswordDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new UserNewPasswordDTO(
                    $form->get('password')->getData()
                );
            },
            'validation_groups' => ['resetPassword']
        ]);
    }
}
