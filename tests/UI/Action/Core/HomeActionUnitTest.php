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

namespace App\Tests\UI\Action\Core;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Action\Core\HomeAction;
use App\UI\Action\Core\Interfaces\HomeActionInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * Class HomeActionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class HomeActionUnitTest extends TestCase
{
    /**
     * @var PresenterInterface
     */
    private $homePresenter;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->redisTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->twig = $this->createMock(Environment::class);

        $this->twig->method('getCharset')->willReturn('utf-8');

        $this->homePresenter = new Presenter($this->redisTranslationRepository);
    }

    public function testItImplements()
    {
        $homeAction = new HomeAction();

        static::assertInstanceOf(HomeActionInterface::class, $homeAction);
    }
}
