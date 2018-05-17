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

namespace App\Tests\Application\Validator;

use App\Application\Validator\UserExist;
use App\Application\Validator\UserExistValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

/**
 * Class UserExistTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserExistTest extends TestCase
{
    public function testItExtends()
    {
        $userExist = new UserExist();

        static::assertInstanceOf(Constraint::class, $userExist);
    }

    public function testViolationMessage()
    {
        $userExist = new UserExist();

        $userExist->message = 'Toto';

        static::assertSame('Toto', $userExist->message);
    }

    public function testValidatedBy()
    {
        $userExist = new UserExist();

        static::assertSame(UserExistValidator::class, $userExist->validatedBy());
    }

    public function testValidationScope()
    {
        $userExist = new UserExist();

        static::assertSame('class', $userExist->getTargets());
    }
}
