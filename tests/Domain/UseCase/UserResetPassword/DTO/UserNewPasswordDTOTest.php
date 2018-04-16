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

namespace App\Tests\Domain\UseCase\UserResetPassword\DTO;

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserNewPasswordDTOInterface;
use App\Domain\UseCase\UserResetPassword\DTO\UserNewPasswordDTO;
use PHPUnit\Framework\TestCase;

/**
 * Class UserNewPasswordDTOTest.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserNewPasswordDTOTest extends TestCase
{
    public function testDataSubmission()
    {
        $userNewPasswordDTO = new UserNewPasswordDTO('Ie1FDLTOTO');

        static::assertInstanceOf(UserNewPasswordDTOInterface::class, $userNewPasswordDTO);
        static::assertSame('Ie1FDLTOTO', $userNewPasswordDTO->password);
    }
}
