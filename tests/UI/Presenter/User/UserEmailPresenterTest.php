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

namespace App\Tests\UI\Presenter\User;

use App\Domain\Models\Interfaces\UserInterface;
use App\UI\Presenter\User\UserEmailPresenter;
use PHPUnit\Framework\TestCase;

/**
 * Class UserEmailPresenterTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserEmailPresenterTest extends TestCase
{
    public function testOptionsAreDefined()
    {
        $userEmailPresenter = new UserEmailPresenter();

        static::assertNull($userEmailPresenter->getUser());
        static::assertArrayHasKey('content', $userEmailPresenter->getEmail());
        static::assertArrayHasKey('subject', $userEmailPresenter->getEmail());
        static::assertArrayHasKey('to', $userEmailPresenter->getEmail());
        static::assertArrayHasKey('link', $userEmailPresenter->getEmail());
    }

    public function testUserIsReturned()
    {
        $userEmailPresenter = new UserEmailPresenter();
        $userEmailPresenter->prepareOptions([
            'user' => $this->createMock(UserInterface::class)
        ]);

        static::assertInstanceOf(
            UserInterface::class,
            $userEmailPresenter->getUser()
        );
    }
}
