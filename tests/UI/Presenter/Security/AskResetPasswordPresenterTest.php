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

namespace App\Tests\UI\Presenter\Security;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Security\AskResetPasswordPresenter;
use App\UI\Presenter\Security\Interfaces\AskResetPasswordPresenterInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class AskResetPasswordPresenterTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordPresenterTest extends TestCase
{
    public function testItImplements()
    {
        $askResetPasswordPresenter = new AskResetPasswordPresenter();

        static::assertInstanceOf(PresenterInterface::class, $askResetPasswordPresenter);
        static::assertInstanceOf(AskResetPasswordPresenterInterface::class, $askResetPasswordPresenter);
    }
}
