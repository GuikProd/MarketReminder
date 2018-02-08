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

namespace tests\Interactor;

use PHPUnit\Framework\TestCase;
use App\Interactor\UserInteractor;

/**
 * Class UserInteractorTest;
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserInteractorTest extends TestCase
{
    public function testSecurityMethods()
    {
        $userInteractor = new UserInteractor();
        $userInteractor->setActive(true);

        static::assertNull($userInteractor->getSalt());
        static::assertTrue($userInteractor->isEnabled());
        static::assertNull($userInteractor->eraseCredentials());
        static::assertTrue($userInteractor->isAccountNonLocked());
        static::assertTrue($userInteractor->isAccountNonExpired());
        static::assertTrue($userInteractor->isCredentialsNonExpired());
    }
}
