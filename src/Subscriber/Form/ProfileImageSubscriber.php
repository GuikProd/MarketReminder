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
use App\Helper\Interfaces\Image\ImageUploaderHelperInterface;
use App\Helper\Interfaces\Image\ImageRetrieverHelperInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;
use App\Helper\Interfaces\Image\ImageTypeCheckerHelperInterface;
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
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ImageBuilderInterface
     */
    private $imageBuilder;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelper;

    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyser;

    /**
     * @var ImageRetrieverHelperInterface
     */
    private $imageRetrieverHelper;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriber;

    /**
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoterHelper;

    /**
     * @var ImageTypeCheckerHelperInterface
     */
    private $imageTypeCheckerHelper;

    /**
     * ProfileImageSubscriber constructor.
     *
     * @param TranslatorInterface                 $translator
     * @param ImageBuilderInterface               $imageBuilder
     * @param ImageUploaderHelperInterface        $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface  $cloudVisionAnalyser
     * @param ImageRetrieverHelperInterface       $imageRetrieverHelper
     * @param CloudVisionDescriberHelperInterface $cloudVisionDescriber
     * @param CloudVisionVoterHelperInterface     $cloudVisionVoterHelper
     * @param ImageTypeCheckerHelperInterface     $imageTypeCheckerHelper
     */
    public function __construct(
        TranslatorInterface $translator,
        ImageBuilderInterface $imageBuilder,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyser,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriber,
        CloudVisionVoterHelperInterface $cloudVisionVoterHelper,
        ImageTypeCheckerHelperInterface $imageTypeCheckerHelper
    ) {
        $this->translator = $translator;
        $this->imageBuilder = $imageBuilder;
        $this->imageUploaderHelper = $imageUploaderHelper;
        $this->cloudVisionAnalyser = $cloudVisionAnalyser;
        $this->imageRetrieverHelper = $imageRetrieverHelper;
        $this->cloudVisionDescriber = $cloudVisionDescriber;
        $this->cloudVisionVoterHelper = $cloudVisionVoterHelper;
        $this->imageTypeCheckerHelper = $imageTypeCheckerHelper;
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

        if (!$this->imageTypeCheckerHelper->checkType($event->getData())) {
            $event->getForm()->addError(
                new FormError(
                    $this->translator->trans(
                        'form.format_error', [], 'validators'
                    )
                )
            );

            return;
        }

        $this->imageUploaderHelper
             ->store($event->getData());

        $analysedImage = $this->cloudVisionAnalyser
                              ->analyse(
                                  $this->imageUploaderHelper->getFilePath()
                                  .
                                  $this->imageUploaderHelper->getFileName(),
                                  'LABEL_DETECTION'
                              );

        $labels = $this->cloudVisionDescriber->describe($analysedImage)->labels();

        if (!$this->cloudVisionVoterHelper->obtainLabel($labels)->vote()) {
            $event->getForm()->addError(
                new FormError(
                    $this->translator->trans(
                        'form.image.label_error', [], 'validators'
                    )
                )
            );

            return;
        }

        $this->imageUploaderHelper->upload();

        $this->imageBuilder
             ->createImage()
             ->withCreationDate(new \DateTime())
             ->withAlt($this->imageUploaderHelper->getFileName())
             ->withPublicUrl(
                 $this->imageRetrieverHelper->getGoogleStoragePublicUrl()
                 .
                 $this->imageUploaderHelper->getFileName()
             );

        $event->getForm()
              ->getParent()
              ->getData()
              ->setProfileImage($this->imageBuilder->getImage());
    }
}
