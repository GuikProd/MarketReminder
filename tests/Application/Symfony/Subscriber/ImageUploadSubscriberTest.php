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

namespace tests\Application\Symfony\Subscriber;

use App\Application\Symfony\Subscriber\ImageUploadSubscriber;
use App\Bridge\CloudStorageBridge;
use App\Bridge\CloudVisionBridge;
use App\Domain\UseCase\UserRegistration\DTO\ImageRegistrationDTO;
use App\Helper\CloudStorage\CloudStoragePersisterHelper;
use App\Helper\CloudStorage\CloudStorageRetrieverHelper;
use App\Helper\CloudVision\CloudVisionDescriberHelper;
use App\Helper\Image\ImageRetrieverHelper;
use App\Helper\Image\ImageUploaderHelper;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;
use App\Helper\Interfaces\Image\ImageRetrieverHelperInterface;
use App\Helper\Interfaces\Image\ImageUploaderHelperInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ImageUploadSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadSubscriberTest extends KernelTestCase
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelper;

    /**
     * @var CloudVisionDescriberHelperInterface
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
    public function setUp()
    {
        $this->translator = static::bootKernel()->getContainer()->get('translator');

        $cloudStorageBridge = new CloudStorageBridge(static::$kernel->getContainer()->getParameter('cloud.storage_credentials'));
        $cloudVisionBridge = new CloudVisionBridge(static::$kernel->getContainer()->getParameter('cloud.vision_credentials'));

        $cloudStoragePersisterHelper = new CloudStoragePersisterHelper($cloudStorageBridge);
        $cloudStorageRetrieverHelper = new CloudStorageRetrieverHelper($cloudStorageBridge);

        $this->imageUploaderHelper = new ImageUploaderHelper(
            static::$kernel->getContainer()->getParameter('kernel.images_dir'),
            static::$kernel->getContainer()->getParameter('cloud.storage.bucket_name'),
            $cloudStoragePersisterHelper
        );

        $this->cloudVisionAnalyser = new CloudVisionDescriberHelper($cloudVisionBridge);

        $this->imageRetrieverHelper = new ImageRetrieverHelper(
            static::$kernel->getContainer()->getParameter('cloud.storage.bucket_name'),
            $cloudStorageRetrieverHelper,
            static::$kernel->getContainer()->getParameter('cloud.storage.public_url')
        );

        $this->cloudVisionDescriber = new CloudVisionDescriberHelper($cloudVisionBridge);

        parent::setUp();
    }

    public function testSubscribedEvents()
    {
        $imageUploadSubscriber = new ImageUploadSubscriber(
            $this->translator,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $this->imageRetrieverHelper,
            $this->cloudVisionDescriber
        );

        static::assertArrayHasKey(FormEvents::SUBMIT, $imageUploadSubscriber::getSubscribedEvents());
        static::assertArrayHasKey(FormEvents::POST_SUBMIT, $imageUploadSubscriber::getSubscribedEvents());
    }

    public function testOnSubmitWithWrongData()
    {
        $imageUploadSubscriber = new ImageUploadSubscriber(
            $this->translator,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $this->imageRetrieverHelper,
            $this->cloudVisionDescriber
        );
    }

    public function testOnPostSubmitWithWrongData()
    {
        $imageUploadSubscriber = new ImageUploadSubscriber(
            $this->translator,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $this->imageRetrieverHelper,
            $this->cloudVisionDescriber
        );

        $postSubmitEvent = $this->createMock(FormEvent::class);
        $postSubmitEvent->method('getData')
                        ->willReturn(null);

        static::assertNull(
            $imageUploadSubscriber->onPostSubmit($postSubmitEvent)
        );
    }

    public function testOnPostSubmitWithGoodData()
    {
        $imageUploadSubscriber = new ImageUploadSubscriber(
            $this->translator,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $this->imageRetrieverHelper,
            $this->cloudVisionDescriber
        );

        $postSubmitEvent = $this->createMock(FormEvent::class);
        $uploadedFile = new File(
            static::$kernel->getContainer()->getParameter('kernel.project_dir').'/tests/_assets/1b6b7932ce444e86daacd4f8c598b001.png',
            true
        );

        $postSubmitEvent->method('getData')
                        ->willReturn(['file' => $uploadedFile]);

        static::assertInstanceOf(
            ImageRegistrationDTO::class,
            $imageUploadSubscriber->onSubmit($postSubmitEvent)
        );
    }
}
