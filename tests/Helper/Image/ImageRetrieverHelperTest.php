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

namespace tests\Helper;

use App\Helper\Image\ImageRetrieverHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Helper\CloudStorage\CloudStorageRetrieverHelper;

/**
 * Class ImageRetrieverHelperTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageRetrieverHelperTest extends KernelTestCase
{
    /**
     * @var string
     */
    private $bucketName;

    /**
     * @var string
     */
    private $publicStorageUrl;

    /**
     * @var CloudStorageRetrieverHelper
     */
    private $cloudRetrieverHelper;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->bucketName = getenv('GOOGLE_BUCKET_NAME');
        $this->publicStorageUrl = getenv('GOOGLE_STORAGE_URL');

        $this->cloudRetrieverHelper = $this->createMock(CloudStorageRetrieverHelper::class);
    }

    public function testConfigValues()
    {
        $imageRetrieverHelper = new ImageRetrieverHelper(
                                    $this->bucketName,
                                    $this->cloudRetrieverHelper,
                                    $this->publicStorageUrl
                                );

        static::assertSame(
            $this->bucketName,
            $imageRetrieverHelper->getBucketName()
        );

        static::assertSame(
            $this->publicStorageUrl,
            $imageRetrieverHelper->getGoogleStoragePublicUrl()
        );
    }
}
