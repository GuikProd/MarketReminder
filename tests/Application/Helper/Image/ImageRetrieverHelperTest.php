<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Application\Helper\Image;

use App\Application\Helper\Image\ImageRetrieverHelper;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageRetrieverHelper;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageRetrieverHelperInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageRetrieverHelperTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ImageRetrieverHelperTest extends TestCase
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

        $this->cloudRetrieverHelper = $this->createMock(CloudStorageRetrieverHelperInterface::class);
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
