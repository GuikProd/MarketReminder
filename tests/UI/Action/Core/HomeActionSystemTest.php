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
use App\UI\Responder\Core\HomeResponder;
use App\UI\Responder\Core\Interfaces\HomeResponderInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

/**
 * Class HomeActionSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeActionSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var HomeActionInterface
     */
    private $homeAction;

    /**
     * @var HomePresenterInterface
     */
    private $homePresenter;

    /**
     * @var HomeResponderInterface
     */
    private $homeResponder;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->request = new Request();
        $this->request::create('/', 'GET');
        $this->twig = static::$kernel->getContainer()->get('twig');

        $this->homeAction = new HomeAction();
        $this->homePresenter = new HomePresenter();
        $this->homeResponder = new HomeResponder($this->twig, $this->homePresenter);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testBlackfireProfilingWithTemplateReturn()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1.5mb', 'HomeAction memory usage for template return');
        $configuration->assert('main.network_in == 0', 'HomeAction network call for template return');
        $configuration->assert('main.network_out == 0', 'HomeAction network callees for template return');

        $this->assertBlackfire($configuration, function () {
            $homeAction = new HomeAction();

            $homeAction($this->request, $this->homeResponder);
        });
    }
}
