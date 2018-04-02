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

namespace App\Tests\Domain\UseCase\UserRegistration\DTO;

use App\Domain\UseCase\UserRegistration\DTO\ImageRegistrationDTO;
use App\Domain\UseCase\UserRegistration\DTO\Interfaces\ImageRegistrationDTOInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageRegistrationDTOTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageRegistrationDTOTest extends TestCase
{
    public function testObjectInitialization()
    {
        $imageRegistrationDTO = new ImageRegistrationDTO(
            'toto',
            'toto.png',
            'http://test.com/profileImage/toto.png'
        );

        static::assertInstanceOf(ImageRegistrationDTOInterface::class, $imageRegistrationDTO);
        static::assertSame('toto', $imageRegistrationDTO->alt);
        static::assertSame('toto.png', $imageRegistrationDTO->filename);
        static::assertSame('http://test.com/profileImage/toto.png', $imageRegistrationDTO->publicUrl);
    }
}
