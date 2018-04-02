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

use App\Domain\Builder\ImageBuilder;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Models\Interfaces\ImageInterface;
use App\Application\Bridge\CloudStorageBridge;
use App\Application\Bridge\CloudVisionBridge;
use App\Application\Helper\CloudStorage\CloudStoragePersisterHelper;
use App\Application\Helper\CloudStorage\CloudStorageRetrieverHelper;
use App\Application\Helper\CloudVision\CloudVisionAnalyserHelper;
use App\Application\Helper\CloudVision\CloudVisionDescriberHelper;
use App\Application\Helper\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Application\Helper\Image\ImageRetrieverHelper;
use App\Application\Helper\Image\ImageUploaderHelper;
use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Application\Symfony\Subscriber\ImageUploadSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
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
     * @var ImageBuilderInterface
     */
    private $imageBuilder;

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

        $this->imageBuilder = new ImageBuilder();

        $this->imageUploaderHelper = new ImageUploaderHelper(
            static::$kernel->getContainer()->getParameter('kernel.images_dir'),
            static::$kernel->getContainer()->getParameter('cloud.storage.bucket_name'),
            $cloudStoragePersisterHelper
        );

        $this->cloudVisionAnalyser = new CloudVisionAnalyserHelper($cloudVisionBridge);

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
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $this->imageRetrieverHelper,
            $this->cloudVisionDescriber
        );

        static::assertArrayHasKey(FormEvents::SUBMIT, $imageUploadSubscriber::getSubscribedEvents());
    }

    public function testOnSubmitWithWrongData()
    {
        $imageUploadSubscriber = new ImageUploadSubscriber(
            $this->translator,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $this->imageRetrieverHelper,
            $this->cloudVisionDescriber
        );

        $imageUploadType = $this->createMock(FormInterface::class);
        $uploadedFile = new File(
            static::$kernel->getContainer()->getParameter('kernel.project_dir').'/tests/_assets/money-world-orig.jpg',
            true
        );

        $postSubmitEvent = new FormEvent($imageUploadType, ['file' => $uploadedFile]);

        $imageUploadSubscriber->onSubmit($postSubmitEvent);

        static::assertArrayHasKey(
            'file',
            $postSubmitEvent->getData()
        );

        static::assertInstanceOf(
            \SplFileInfo::class,
            $postSubmitEvent->getData()['file']
        );
    }

    public function testOnSubmitWithGoodData()
    {
        $imageUploadSubscriber = new ImageUploadSubscriber(
            $this->translator,
            $this->imageBuilder,
            $this->imageUploaderHelper,
            $this->cloudVisionAnalyser,
            $this->imageRetrieverHelper,
            $this->cloudVisionDescriber
        );

        $imageUploadType = $this->createMock(FormInterface::class);
        $uploadedFile = new File(
            static::$kernel->getContainer()->getParameter('kernel.project_dir').'/tests/_assets/1b6b7932ce444e86daacd4f8c598b001.png',
            true
        );

        $postSubmitEvent = new FormEvent($imageUploadType, ['file' => $uploadedFile]);

        $imageUploadSubscriber->onSubmit($postSubmitEvent);

        static::assertInstanceOf(
            ImageInterface::class,
            $postSubmitEvent->getData()
        );
    }
}
