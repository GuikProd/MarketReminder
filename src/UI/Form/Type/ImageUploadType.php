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

use App\Application\Symfony\Subscriber\Interfaces\ImageUploadSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImageUploadType.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadType extends AbstractType
{
    /**
     * @var ImageUploadSubscriberInterface
     */
    private $imageUploadSubscriber;

    /**
     * ImageUploadType constructor.
     *
     * @param ImageUploadSubscriberInterface $imageUploadSubscriber
     */
    public function __construct(ImageUploadSubscriberInterface $imageUploadSubscriber)
    {
        $this->imageUploadSubscriber = $imageUploadSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class)
            ->addEventSubscriber($this->imageUploadSubscriber)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['imageUpload']
        ]);
    }
}
