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

namespace App\Tests\UI\Action\Core;

use App\UI\Action\Core\HomeAction;
use App\UI\Action\Core\Interfaces\HomeActionInterface;
use App\UI\Presenter\Core\HomePresenter;
use App\UI\Presenter\Core\Interfaces\HomePresenterInterface;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * Class HomeActionUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeActionUnitTest extends TestCase
{
    /**
     * @var HomePresenterInterface
     */
    private $homePresenter;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->homePresenter = new HomePresenter();
        $this->twig = $this->createMock(Environment::class);

        $this->twig->method('getCharset')->willReturn('utf-8');
    }

    public function testItImplements()
    {
        $homeAction = new HomeAction();

        static::assertInstanceOf(HomeActionInterface::class, $homeAction);
    }
}
