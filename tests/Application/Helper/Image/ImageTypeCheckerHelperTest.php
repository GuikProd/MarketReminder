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

namespace App\Tests\Application\Helper\Image;

use App\Application\Helper\Image\ImageTypeCheckerHelper;
use PHPUnit\Framework\TestCase;
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

        static::assertTrue(
            ImageTypeCheckerHelper::checkType($uploadedFileMock)
        );
    }

    public function testSaveIsNotAllowed()
    {
        $uploadedFileMock = $this->createMock(UploadedFile::class);
        $uploadedFileMock->method('getMimeType')
                         ->willReturn('image/gif');

        static::assertFalse(
            ImageTypeCheckerHelper::checkType($uploadedFileMock)
        );
    }
}
