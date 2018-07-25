<?php

declare(strict_types = 1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@hotmail.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\UI\Form\Type;

use App\Domain\UseCase\UserLogin\DTO\Interfaces\UserLoginDTOInterface;
use App\UI\Form\Type\LoginType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class LoginTypeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@hotmail.fr>
 */
final class LoginTypeUnitTest extends TypeTestCase
{
    public function testItReceiveWrongData()
    {
        $form = $this->factory->create(LoginType::class);

        $form->submit([
            'username' => 'us',
            'password' => 'password'
        ]);

        static::assertTrue($form->isSynchronized());
        static::assertTrue($form->isValid());
        static::assertCount(0, $form->getErrors());
        static::assertInstanceOf(
            UserLoginDTOInterface::class,
            $form->getData()
        );
    }

    public function testItReceiveValidData()
    {
        $form = $this->factory->create(LoginType::class);

        $form->submit([
            'username' => 'username',
            'password' => 'password'
        ]);

        static::assertTrue($form->isSynchronized());
        static::assertTrue($form->isValid());
        static::assertInstanceOf(
            UserLoginDTOInterface::class,
            $form->getData()
        );
    }
}
