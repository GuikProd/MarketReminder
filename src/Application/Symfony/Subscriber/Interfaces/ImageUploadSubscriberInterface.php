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

namespace App\Application\Symfony\Subscriber\Interfaces;

use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;
use App\Helper\Interfaces\Image\ImageRetrieverHelperInterface;
use App\Helper\Interfaces\Image\ImageUploaderHelperInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface ImageUploadSubscriberInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageUploadSubscriberInterface
{
    /**
     * ImageUploadSubscriberInterface constructor.
     *
     * @param TranslatorInterface                  $translator
     * @param ImageBuilderInterface                $imageBuilder
     * @param ImageUploaderHelperInterface         $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface   $cloudVisionAnalyser
     * @param ImageRetrieverHelperInterface        $imageRetrieverHelper
     * @param CloudVisionDescriberHelperInterface  $cloudVisionDescriber
     */
    public function __construct(
        TranslatorInterface $translator,
        ImageBuilderInterface $imageBuilder,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyser,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriber
    );

    /**
     * Allow to check the UploadedFile using CloudVision API
     * and store it into the GCP bucket, a local copy is created in order
     * to ease the upload process.
     *
     * Please note that the "local" version of the file is deleted
     * once the upload is done.
     *
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event): void;
}
