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

namespace App\Application\Symfony\Subscriber;

use App\Application\Symfony\Subscriber\Interfaces\ImageUploadSubscriberInterface;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\UseCase\UserRegistration\DTO\ImageRegistrationDTO;
use App\Helper\CloudVision\CloudVisionVoterHelper;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;
use App\Helper\Interfaces\Image\ImageUploaderHelperInterface;
use App\Helper\Interfaces\Image\ImageRetrieverHelperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ImageUploadSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadSubscriber implements ImageUploadSubscriberInterface, EventSubscriberInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        TranslatorInterface $translator,
        ImageBuilderInterface $imageBuilder,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyser,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriber
    ) {
        $this->translator = $translator;
        $this->imageBuilder = $imageBuilder;
        $this->imageUploaderHelper = $imageUploaderHelper;
        $this->cloudVisionAnalyser = $cloudVisionAnalyser;
        $this->imageRetrieverHelper = $imageRetrieverHelper;
        $this->cloudVisionDescriber = $cloudVisionDescriber;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
            FormEvents::POST_SUBMIT => 'onPostSubmit'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onSubmit(FormEvent $event): void
    {
        if (is_null($event->getData()['file'])) {
            return;
        }

        $this->imageUploaderHelper->store($event->getData()['file']);

        $analysedImage = $this->cloudVisionAnalyser
                              ->analyse(
                                  $this->imageUploaderHelper->getFilePath()
                                  .
                                  $this->imageUploaderHelper->getFileName(),
                                  'LABEL_DETECTION'
                              );

        $labels = $this->cloudVisionDescriber->describe($analysedImage)->labels();

        $this->cloudVisionDescriber->obtainLabel($labels);

        foreach ($this->cloudVisionDescriber->getLabels() as $label) {
            if (!CloudVisionVoterHelper::vote($label)) {
                $event->getForm()->addError(
                    new FormError(
                        $this->translator->trans(
                            'form.image.label_error', [], 'validators'
                        )
                    )
                );

                return;
            }
        }

        $this->imageUploaderHelper->upload();

        $imageRegistrationDTO = new ImageRegistrationDTO(
            $this->imageUploaderHelper->getFileName(),
            $this->imageUploaderHelper->getFileName(),
            $this->imageUploaderHelper->getFilePath()
        );

        $event->setData($imageRegistrationDTO);
    }

    /**
     * {@inheritdoc}
     */
    public function onPostSubmit(FormEvent $postSubmitEvent): void
    {
        if (!$postSubmitEvent->getData() instanceof ImageRegistrationDTO) {
            return;
        }

        $this->imageBuilder->build(
            $postSubmitEvent->getData()->alt,
            $postSubmitEvent->getData()->filename,
            $postSubmitEvent->getData()->publicUrl
        );

        $postSubmitEvent->setData($this->imageBuilder->getImage());
    }
}
