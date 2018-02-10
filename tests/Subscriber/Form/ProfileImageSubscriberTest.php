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

namespace tests\Subscriber\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use App\Subscriber\Form\ProfileImageSubscriber;
use App\Builder\Interfaces\ImageBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Helper\Interfaces\ImageRetrieverHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionVoterHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;

/**
 * Class ProfileImageSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProfileImageSubscriberTest extends KernelTestCase
{
    public function testSubscribedEvents()
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $imageBuilderMock = $this->createMock(ImageBuilderInterface::class);
        $imageUploaderMock = $this->createMock(ImageUploaderHelperInterface::class);
        $imageRetrieverMock = $this->createMock(ImageRetrieverHelperInterface::class);
        $cloudVisionVoterMock = $this->createMock(CloudVisionVoterHelperInterface::class);
        $cloudVisionAnalyserMock = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $cloudVisionDescriberMock = $this->createMock(CloudVisionDescriberHelperInterface::class);

        $profileImageSubscriber = new ProfileImageSubscriber(
            $translatorMock,
            $imageBuilderMock,
            $cloudVisionVoterMock,
            $imageUploaderMock,
            $cloudVisionAnalyserMock,
            $imageRetrieverMock,
            $cloudVisionDescriberMock
        );

        static::assertArrayHasKey(
            FormEvents::SUBMIT,
            $profileImageSubscriber::getSubscribedEvents()
        );
    }

    public function testEmptyData()
    {
        $eventsMock = $this->createMock(FormEvent::class);
        $formMock = $this->createMock(FormInterface::class);
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $imageBuilderMock = $this->createMock(ImageBuilderInterface::class);
        $imageUploaderMock = $this->createMock(ImageUploaderHelperInterface::class);
        $imageRetrieverMock = $this->createMock(ImageRetrieverHelperInterface::class);
        $cloudVisionVoterMock = $this->createMock(CloudVisionVoterHelperInterface::class);
        $cloudVisionAnalyserMock = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $cloudVisionDescriberMock = $this->createMock(CloudVisionDescriberHelperInterface::class);

        $eventsMock->method('getForm')
                   ->willReturn($formMock);

        $profileImageSubscriber = new ProfileImageSubscriber(
            $translatorMock,
            $imageBuilderMock,
            $cloudVisionVoterMock,
            $imageUploaderMock,
            $cloudVisionAnalyserMock,
            $imageRetrieverMock,
            $cloudVisionDescriberMock
        );

        static::assertNull(
            $profileImageSubscriber->onSubmit($eventsMock)
        );
    }

    public function testWrongImageExtension()
    {
        $eventsMock = $this->createMock(FormEvent::class);
        $formMock = $this->createMock(FormInterface::class);
        $uploadedFileMock = $this->createMock(UploadedFile::class);
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $imageBuilderMock = $this->createMock(ImageBuilderInterface::class);
        $imageUploaderMock = $this->createMock(ImageUploaderHelperInterface::class);
        $imageRetrieverMock = $this->createMock(ImageRetrieverHelperInterface::class);
        $cloudVisionVoterMock = $this->createMock(CloudVisionVoterHelperInterface::class);
        $cloudVisionAnalyserMock = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $cloudVisionDescriberMock = $this->createMock(CloudVisionDescriberHelperInterface::class);

        $uploadedFileMock->method('getMimeType')
                         ->willReturn('image/gif');

        $eventsMock->method('getForm')
                   ->willReturn($formMock);

        $eventsMock->method('getData')
                   ->willReturn($uploadedFileMock);

        $profileImageSubscriber = new ProfileImageSubscriber(
            $translatorMock,
            $imageBuilderMock,
            $cloudVisionVoterMock,
            $imageUploaderMock,
            $cloudVisionAnalyserMock,
            $imageRetrieverMock,
            $cloudVisionDescriberMock
        );

        static::assertNull(
            $profileImageSubscriber->onSubmit($eventsMock)
        );
    }
}
