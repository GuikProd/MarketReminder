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

use App\Bridge\CloudVisionBridge;
use App\Bridge\CloudStorageBridge;
use App\Interactor\UserInteractor;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use App\Helper\Image\ImageUploaderHelper;
use Symfony\Component\HttpFoundation\File\File;
use App\Subscriber\Form\ImageUploadSubscriber;
use App\Builder\Interfaces\ImageBuilderInterface;
use App\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Helper\CloudVision\CloudVisionAnalyserHelper;
use App\Helper\CloudVision\CloudVisionDescriberHelper;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Helper\CloudStorage\CloudStoragePersisterHelper;
use App\Helper\Interfaces\Image\ImageUploaderHelperInterface;
use App\Helper\Interfaces\Image\ImageRetrieverHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;
use App\Helper\Interfaces\CloudStorage\CloudStoragePersisterHelperInterface;

/**
 * Class ProfileImageSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProfileImageSubscriberTest extends KernelTestCase
{
    /**
     * @var CloudVisionBridgeInterface
     */
    private $cloudVisionBridge;

    /**
     * @var CloudStorageBridge
     */
    private $cloudStorageBridge;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelper;

    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyser;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriber;

    /**
     * @var CloudStoragePersisterHelperInterface
     */
    private $cloudStoragePersister;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->cloudVisionBridge = new CloudVisionBridge(
            static::$kernel->getContainer()->getParameter('cloud.vision_credentials')
        );

        $this->cloudStorageBridge = new CloudStorageBridge(
            static::$kernel->getContainer()->getParameter('cloud.storage_credentials')
        );

        $this->cloudStoragePersister = new CloudStoragePersisterHelper($this->cloudStorageBridge);

        $this->imageUploaderHelper = new ImageUploaderHelper(
            static::$kernel->getContainer()->getParameter('kernel.images_dir'),
            getenv('GOOGLE_BUCKET_NAME'),
            $this->cloudStoragePersister
        );
        $this->cloudVisionAnalyser = new CloudVisionAnalyserHelper($this->cloudVisionBridge);
        $this->cloudVisionDescriber = new CloudVisionDescriberHelper($this->cloudVisionBridge);
    }

    public function testSubscribedEvents()
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $imageBuilderMock = $this->createMock(ImageBuilderInterface::class);
        $imageUploaderMock = $this->createMock(ImageUploaderHelperInterface::class);
        $imageRetrieverMock = $this->createMock(ImageRetrieverHelperInterface::class);
        $cloudVisionAnalyserMock = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $cloudVisionDescriberMock = $this->createMock(CloudVisionDescriberHelperInterface::class);

        $profileImageSubscriber = new ImageUploadSubscriber(
            $translatorMock,
            $imageBuilderMock,
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
        $cloudVisionAnalyserMock = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $cloudVisionDescriberMock = $this->createMock(CloudVisionDescriberHelperInterface::class);

        $eventsMock->method('getForm')
                   ->willReturn($formMock);

        $profileImageSubscriber = new ImageUploadSubscriber(
            $translatorMock,
            $imageBuilderMock,
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
        $cloudVisionAnalyserMock = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $cloudVisionDescriberMock = $this->createMock(CloudVisionDescriberHelperInterface::class);

        $uploadedFileMock->method('getMimeType')
                         ->willReturn('image/gif');

        $eventsMock->method('getForm')
                   ->willReturn($formMock);

        $eventsMock->method('getData')
                   ->willReturn($uploadedFileMock);

        $profileImageSubscriber = new ImageUploadSubscriber(
            $translatorMock,
            $imageBuilderMock,
            $imageUploaderMock,
            $cloudVisionAnalyserMock,
            $imageRetrieverMock,
            $cloudVisionDescriberMock
        );

        static::assertNull(
            $profileImageSubscriber->onSubmit($eventsMock)
        );
    }

    public function testRightImageExtension()
    {
        $eventsMock = $this->createMock(FormEvent::class);
        $formMock = $this->createMock(FormInterface::class);
        $registerFormMock = $this->createMock(FormInterface::class);
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $imageBuilderMock = $this->createMock(ImageBuilderInterface::class);
        $imageRetrieverMock = $this->createMock(ImageRetrieverHelperInterface::class);

        $userInteractor = new UserInteractor();
        $userInteractor->setUsername('Toto');
        $userInteractor->setEmail('toto@gmail.com');
        $userInteractor->setPlainPassword('Ie1FDLTOTO');

        $uploadedFile = new File(
            static::$kernel->getContainer()
                                ->getParameter('kernel.project_dir')
                                .
                                '/tests/_assets/1b6b7932ce444e86daacd4f8c598b001.png'
        );

        $registerFormMock->method('getData')
                         ->willReturn($userInteractor);

        $formMock->method('getParent')
                 ->willReturn($registerFormMock);

        $eventsMock->method('getForm')
                   ->willReturn($formMock);

        $eventsMock->method('getData')
                   ->willReturn($uploadedFile);

        $profileImageSubscriber = new ImageUploadSubscriber(
            $translatorMock,
            $imageBuilderMock,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $imageRetrieverMock,
            $this->cloudVisionDescriber
        );

        static::assertNull(
            $profileImageSubscriber->onSubmit($eventsMock)
        );
    }

    public function testWrongImageLabel()
    {
        $eventsMock = $this->createMock(FormEvent::class);
        $formMock = $this->createMock(FormInterface::class);
        $registerFormMock = $this->createMock(FormInterface::class);
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $imageBuilderMock = $this->createMock(ImageBuilderInterface::class);
        $imageRetrieverMock = $this->createMock(ImageRetrieverHelperInterface::class);

        $userInteractor = new UserInteractor();
        $userInteractor->setUsername('Toto');
        $userInteractor->setEmail('toto@gmail.com');
        $userInteractor->setPlainPassword('Ie1FDLTOTO');

        $uploadedFile = new File(
            static::$kernel->getContainer()
                ->getParameter('kernel.project_dir')
            .
            '/tests/_assets/money-world-orig.jpg'
        );

        $registerFormMock->method('getData')
                         ->willReturn($userInteractor);

        $formMock->method('getParent')
                 ->willReturn($registerFormMock);

        $eventsMock->method('getForm')
                   ->willReturn($formMock);

        $eventsMock->method('getData')
                   ->willReturn($uploadedFile);

        $profileImageSubscriber = new ImageUploadSubscriber(
            $translatorMock,
            $imageBuilderMock,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $imageRetrieverMock,
            $this->cloudVisionDescriber
        );

        static::assertNull(
            $profileImageSubscriber->onSubmit($eventsMock)
        );
    }
}
