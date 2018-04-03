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

namespace tests\Domain\UseCase\ImageUpload\DTO;

use App\Domain\UseCase\ImageUpload\DTO\ImageUploadDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ImageUploadDTOTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadDTOTest extends TestCase
{
    public function testItImplementsAndHasAttributes()
    {
        $imageUploadDTO = new ImageUploadDTO();

        static::assertInstanceOf(
            ImageUploadDTOInterface::class,
            $imageUploadDTO
        );
        static::assertObjectHasAttribute('image', $imageUploadDTO);
    }

    public function testInstantiation()
    {
        $fileMock = $this->createMock(File::class);

        $imageUploadDTO = new ImageUploadDTO($fileMock);

        static::assertInstanceOf(
            \SplFileInfo::class,
            $imageUploadDTO->image
        );
    }
}
