<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@hotmail.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\UI\Form\Type;

use App\Domain\UseCase\UserLogin\DTO\Interfaces\UserLoginDTOInterface;
use App\Domain\UseCase\UserLogin\DTO\UserLoginDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LoginType.
 *
 * @author Guillaume Loulier <guillaume.loulier@hotmail.fr>
 */
final class LoginType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'login.username_label',
                'help' => 'login.username_help',
                'attr' => [
                    'minLength' => 3,
                    'maxLength' => 25
                ],
                '_locale' => $options['_locale']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'login.password_label',
                'help' => 'login.password_help',
                'attr' => [
                    'minLength' => 5,
                    'maxLength' => 25
                ],
                '_locale' => $options['_locale']
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserLoginDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new UserLoginDTO(
                    $form->get('username')->getData(),
                    $form->get('password')->getData()
                );
            },
            'help' => '',
            'validation_groups' => ['login']
        ]);
    }
}
