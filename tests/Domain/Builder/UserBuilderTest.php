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

namespace tests\Domain\Builder;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class UserBuilderTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserBuilderTest extends TestCase
{
    public function testItImplements()
    {
        $userBuilder = new UserBuilder();

        static::assertInstanceOf(
            UserBuilderInterface::class,
            $userBuilder
        );
    }
}
