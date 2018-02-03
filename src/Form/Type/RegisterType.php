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

namespace App\Form\Type;

use App\Models\Interfaces\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;
use App\Subscriber\Interfaces\RegisterCredentialsSubscriberInterface;

/**
 * Class RegisterType.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterType extends AbstractType
{
    /**
     * @var ProfileImageSubscriberInterface
     */
    private $profileImageSubscriber;

    /**
     * @var RegisterCredentialsSubscriberInterface
     */
    private $registerCredentialsSubscriber;

    /**
     * RegisterType constructor.
     *
     * @param ProfileImageSubscriberInterface $profileImageSubscriber
     * @param RegisterCredentialsSubscriberInterface $registerCredentialsSubscriber
     */
    public function __construct(
        ProfileImageSubscriberInterface $profileImageSubscriber,
        RegisterCredentialsSubscriberInterface $registerCredentialsSubscriber
    ) {
        $this->profileImageSubscriber = $profileImageSubscriber;
        $this->registerCredentialsSubscriber = $registerCredentialsSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', PasswordType::class)
            ->add('profileImage', FileType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->addEventSubscriber($this->registerCredentialsSubscriber)
        ;

        $builder->get('profileImage')->addEventSubscriber($this->profileImageSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInterface::class,
            'validation_groups' => [
                'registration',
            ],
        ]);
    }
}
