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

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserResetPasswordDTOInterface;
use App\Domain\UseCase\UserResetPassword\DTO\UserResetPasswordDTO;
use App\Infra\Form\FormSubscriber\Interfaces\AskResetPasswordTypeSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AskResetPasswordType
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordType extends AbstractType
{
    /**
     * @var AskResetPasswordTypeSubscriberInterface
     */
    private $askResetPasswordTypeSubscriber;

    /**
     * AskResetPasswordType constructor.
     *
     * @param AskResetPasswordTypeSubscriberInterface $askResetPasswordTypeSubscriber
     */
    public function __construct(AskResetPasswordTypeSubscriberInterface $askResetPasswordTypeSubscriber)
    {
        $this->askResetPasswordTypeSubscriber = $askResetPasswordTypeSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
        ;

        $builder->addEventSubscriber($this->askResetPasswordTypeSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['resetPassword'],
            'data_class' => UserResetPasswordDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new UserResetPasswordDTO(
                    $form->get('username')->getData(),
                    $form->get('email')->getData()
                );
            }
        ]);
    }
}
