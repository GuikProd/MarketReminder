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

namespace App\Tests\Application\Validator;

use App\Application\Validator\Interfaces\UserExistValidatorInterface;
use App\Application\Validator\UserExistValidator;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UserExistValidatorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserExistValidatorUnitTest extends TestCase
{
    public function testItExtendsAndImplements()
    {
        $userExistValidator = new UserExistValidator(
            $this->createMock(TranslatorInterface::class),
            $this->createMock(UserRepositoryInterface::class)
        );

        static::assertInstanceOf(
            ConstraintValidator::class,
            $userExistValidator
        );

        static::assertInstanceOf(
            UserExistValidatorInterface::class,
            $userExistValidator
        );

        static::assertClassHasAttribute(
            'translator',
            UserExistValidator::class
        );

        static::assertClassHasAttribute(
            'userRepository',
            UserExistValidator::class
        );
    }
}
