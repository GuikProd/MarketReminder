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

namespace App\Tests\UI\Presenter\Core;

use App\UI\Presenter\AbstractPresenter;
use App\UI\Presenter\Core\HomePresenter;
use App\UI\Presenter\Core\Interfaces\HomePresenterInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class HomePresenterTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomePresenterTest extends TestCase
{
    public function testItImplementsAndExtends()
    {
        $homePresenter = new HomePresenter();

        static::assertInstanceOf(AbstractPresenter::class, $homePresenter);
        static::assertInstanceOf(HomePresenterInterface::class, $homePresenter);
    }

    public function testItDefineDefaultOptions()
    {
        $homePresenter = new HomePresenter();
        $homePresenter->prepareOptions([
            'page' => [
                'title' => 'home.title'
            ]
        ]);

        static::assertArrayHasKey('title', $homePresenter->getPage());
    }
}
