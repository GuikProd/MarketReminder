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
use App\Helper\Image\ImageUploaderHelper;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;
use App\Helper\Interfaces\Image\ImageRetrieverHelperInterface;
use App\Helper\Interfaces\Image\ImageUploaderHelperInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormEvents;
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
        $this->imageUploaderHelper = $this->createMock(ImageUploaderHelper::class);
        $this->cloudVisionAnalyser = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $this->imageRetrieverHelper = $this->createMock(ImageRetrieverHelperInterface::class);
        $this->cloudVisionDescriber = $this->createMock(CloudVisionDescriberHelperInterface::class);

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
}
