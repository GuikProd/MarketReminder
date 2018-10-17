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

use App\UI\Action\Core\HomeAction;
use App\UI\Action\Core\Interfaces\HomeActionInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
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
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class HomeActionSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

    /**
     * @var HomeActionInterface|null
     */
    private $homeAction = null;

    /**
     * @var HomeResponderInterface|null
     */
    private $homeResponder = null;

    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * @var Environment|null
     */
    private $twig = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->request = Request::create('/', 'GET');
        $this->request->setLocale('fr');

        $this->presenter = static::$container->get(PresenterInterface::class);
        $this->twig = static::$kernel->getContainer()->get('twig');

        $this->homeResponder = new HomeResponder($this->twig, $this->presenter);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testResponseIsReturned()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1.5MB', 'HomeAction memory usage for template return');
        $configuration->assert('main.network_in == 0', 'HomeAction network call for template return');
        $configuration->assert('main.network_out == 0', 'HomeAction network callees for template return');

        $action = new HomeAction();

        $this->assertBlackfire($configuration, function () use ($action) {
            $action($this->request, $this->homeResponder);
        });
    }
}
