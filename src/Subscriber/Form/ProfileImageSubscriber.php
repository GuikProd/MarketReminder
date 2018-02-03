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

namespace App\Subscriber\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\TranslatorInterface;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;

/**
 * Class ProfileImageSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProfileImageSubscriber implements ProfileImageSubscriberInterface
{
    const AVAILABLE_TYPES = ['image/jpeg', 'image/png'];

    /**
     * @var TranslatorInterface
     */
    private $translatorInterface;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelperInterface;

    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyserInterface;

    /**
     * ProfileImageSubscriber constructor.
     *
     * @param TranslatorInterface                   $translatorInterface
     * @param ImageUploaderHelperInterface          $imageUploaderHelperInterface
     * @param CloudVisionAnalyserHelperInterface    $cloudVisionAnalyserInterface
     */
    public function __construct(
        TranslatorInterface $translatorInterface,
        ImageUploaderHelperInterface $imageUploaderHelperInterface,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserInterface
    ) {
        $this->translatorInterface = $translatorInterface;
        $this->imageUploaderHelperInterface = $imageUploaderHelperInterface;
        $this->cloudVisionAnalyserInterface = $cloudVisionAnalyserInterface;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
            FormEvents::POST_SUBMIT => 'uploadAndAnalyseImage'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onSubmit(FormEvent $event): void
    {
        if ($event->getData() === null) {
            return;
        }

        if (!in_array($event->getData()->getMimeType(), self::AVAILABLE_TYPES)) {
            $event->getForm()->addError(
                new FormError(
                    $this->translatorInterface->trans(
                        'form.format_error', [], 'validators'
                    )
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function uploadAndAnalyseImage(FormEvent $event): bool
    {
        if ($event->getData() === null) {
            return false;
        }

        $this->imageUploaderHelperInterface
             ->store($event->getData());

        $analysedImage = $this->cloudVisionAnalyserInterface
                              ->analyse(
                                  $this->imageUploaderHelperInterface->getFilePath()
                                  .
                                  $this->imageUploaderHelperInterface->getFileName(),
                                  'LABEL_DETECTION'
                              );

        $this->cloudVisionAnalyserInterface->describe($analysedImage);
    }
}
