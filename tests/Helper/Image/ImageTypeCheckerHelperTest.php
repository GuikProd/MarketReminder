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

namespace tests\Helper\Image;

use PHPUnit\Framework\TestCase;
use App\Helper\Image\ImageTypeCheckerHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageTypeCheckerHelperTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageTypeCheckerHelperTest extends TestCase
{
    public function testSaveIsAllowed()
    {
        $uploadedFileMock = $this->createMock(UploadedFile::class);
        $uploadedFileMock->method('getMimeType')
                         ->willReturn('image/png');

        $imageTypeCheckerMock = new ImageTypeCheckerHelper();

        static::assertTrue(
            $imageTypeCheckerMock->checkType($uploadedFileMock)
        );
    }

    public function testSaveIsNotAllowed()
    {
        $uploadedFileMock = $this->createMock(UploadedFile::class);
        $uploadedFileMock->method('getMimeType')
                         ->willReturn('image/gif');

        $imageTypeCheckerMock = new ImageTypeCheckerHelper();

        static::assertFalse(
            $imageTypeCheckerMock->checkType($uploadedFileMock)
        );
    }
}
