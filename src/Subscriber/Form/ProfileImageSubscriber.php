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
use App\Builder\Interfaces\ImageBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Helper\Interfaces\ImageRetrieverHelperInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionVoterHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;

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
     * @var ImageBuilderInterface
     */
    private $imageBuilderInterface;

    /**
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoterHelper;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelperInterface;

    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyserInterface;

    /**
     * @var ImageRetrieverHelperInterface
     */
    private $imageRetrieverHelperInterface;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriberInterface;

    /**
     * ProfileImageSubscriber constructor.
     *
     * @param TranslatorInterface                    $translatorInterface
     * @param ImageBuilderInterface                  $imageBuilderInterface
     * @param CloudVisionVoterHelperInterface        $cloudVisionVoterHelper
     * @param ImageUploaderHelperInterface           $imageUploaderHelperInterface
     * @param CloudVisionAnalyserHelperInterface     $cloudVisionAnalyserInterface
     * @param ImageRetrieverHelperInterface          $imageRetrieverHelperInterface
     * @param CloudVisionDescriberHelperInterface    $cloudVisionDescriberInterface
     */
    public function __construct(
        TranslatorInterface $translatorInterface,
        ImageBuilderInterface $imageBuilderInterface,
        CloudVisionVoterHelperInterface $cloudVisionVoterHelper,
        ImageUploaderHelperInterface $imageUploaderHelperInterface,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserInterface,
        ImageRetrieverHelperInterface $imageRetrieverHelperInterface,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberInterface
    ) {
        $this->translatorInterface = $translatorInterface;
        $this->imageBuilderInterface = $imageBuilderInterface;
        $this->cloudVisionVoterHelper = $cloudVisionVoterHelper;
        $this->imageUploaderHelperInterface = $imageUploaderHelperInterface;
        $this->cloudVisionAnalyserInterface = $cloudVisionAnalyserInterface;
        $this->imageRetrieverHelperInterface = $imageRetrieverHelperInterface;
        $this->cloudVisionDescriberInterface = $cloudVisionDescriberInterface;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'onSubmit'
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

            return;
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

        $labels = $this->cloudVisionDescriberInterface->describe($analysedImage)->labels();

        if (!$this->cloudVisionVoterHelper->obtainLabel($labels)->vote()) {
            $event->getForm()->addError(
                new FormError(
                    $this->translatorInterface->trans(
                        'form.image.label_error', [], 'validators'
                    )
                )
            );

            return;
        }

        $this->imageUploaderHelperInterface->upload();

        $this->imageBuilderInterface
             ->createImage()
             ->withCreationDate(new \DateTime())
             ->withAlt($this->imageUploaderHelperInterface->getFileName())
             ->withPublicUrl(
                 $this->imageRetrieverHelperInterface->getGoogleStoragePublicUrl()
                 .
                 $this->imageUploaderHelperInterface->getFileName()
             );

        $event->getForm()
              ->getParent()
              ->getData()
              ->setProfileImage($this->imageBuilderInterface->getImage());
    }
}
