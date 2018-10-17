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

use App\Domain\Factory\UserFactory;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class UserFactorySystemTest.
 *
 * @package App\Tests\Domain\Factory
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserFactorySystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideCredentials
     *
     * @group Blackfire
     */
    public function testUserCreation(string $username, string $email, string $password)
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 65kB', 'UserFactory instantiation memory usage');

        $factory = new UserFactory();

        $this->assertBlackfire($configuration, function() use($factory, $username, $email, $password) {
            $factory->createFromUI($username, $email, $password);
        });
    }

    /**
     * @return \Generator
     */
    public function provideCredentials()
    {
        yield array('toto', 'toto@gmail.com', 'toto');
        yield array('tutu', 'tutu@gmail.com', 'tutu');
        yield array('titi', 'titi@gmail.com', 'titi');
    }
}
