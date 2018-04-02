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
use App\UI\Form\Type\Interfaces\ImageUploadTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ImageUploadType.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadType extends AbstractType implements ImageUploadTypeInterface
{
    /**
     * @var ImageUploadSubscriberInterface
     */
    private $imageUploadSubscriber;

    /**
     * {@inheritdoc}
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
}
