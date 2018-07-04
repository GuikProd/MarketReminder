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

use App\Domain\UseCase\UserRegistration\DTO\Interfaces\UserRegistrationDTOInterface;
use App\Domain\UseCase\UserRegistration\DTO\UserRegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegisterType.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class RegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'security.registration_username',
                    'aria-label' => 'security.registration_username',
                ],
                'help' => 'register.username_help'
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'security.registration_email',
                    'aria-label' => 'security.registration_email'
                ],
                'help' => 'register.email_help'
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'security.registration_password',
                    'aria-label' => 'security.registration_password'
                ],
                'help' => 'register.password_help'
            ])
            ->add('profileImage', FileType::class, [
                'required' => false,
                'help' => 'register.profileImage_help'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new UserRegistrationDTO(
                    $form->get('username')->getData(),
                    $form->get('email')->getData(),
                    $form->get('password')->getData(),
                    md5(
                        crypt(
                            str_rot13($form->get('username')->getData()),
                            $form->get('email')->getData()
                        )
                    ),
                    $form->get('profileImage')->getData()
                );
            },
            'validation_groups' => ['registration'],
        ]);
    }
}
