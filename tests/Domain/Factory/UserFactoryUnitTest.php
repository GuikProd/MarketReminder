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

namespace App\Tests\Domain\Factory;

use App\Domain\Factory\Interfaces\UserFactoryInterface;
use App\Domain\Factory\UserFactory;
use App\Domain\Models\Interfaces\UserInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UserFactoryUnitTest
 *
 * @package App\Tests\Domain\Factory
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserFactoryUnitTest extends TestCase
{
    public function testItExist()
    {
        $factory = new UserFactory();

        static::assertInstanceOf(UserFactoryInterface::class, $factory);
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @throws \Exception
     *
     * @dataProvider provideCredentials
     */
    public function testItCreate(
        string $username,
        string $email,
        string $password
    ) {
        $factory = new UserFactory();

        $user = $factory->createFromUI($username, $email, $password);

        static::assertInstanceOf(UserInterface::class, $user);
    }

    /**
     * @return \Generator
     */
    public function provideCredentials()
    {
        yield array('toto', 'toto@toto.fr', 'toto');
        yield array('tutu', 'tutu@gmail.com', 'tutu');
        yield array('titi', 'titi@gmail.com', 'titi');
    }
}
