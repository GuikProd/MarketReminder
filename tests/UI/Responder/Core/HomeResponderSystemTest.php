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

namespace App\Tests\UI\Responder\Core;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Core\HomeResponder;
use App\UI\Responder\Core\Interfaces\HomeResponderInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

/**
 * Class HomeResponderSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class HomeResponderSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var HomeResponderInterface|null
     */
    private $homeResponder = null;

    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

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

        $this->twig = static::$container->get('twig');
        $this->presenter = static::$container->get(PresenterInterface::class);

        $this->request = Request::create('/fr/', 'GET');
        $this->homeResponder = new HomeResponder($this->twig, $this->presenter);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItReturnResponse()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 1.8MB', 'Home responder response return memory usage');
        $configuration->assert('main.network_in <= 10B', 'Home responder return response network in');
        $configuration->assert('main.network_out < 90B', 'Home responder return response network out');

        $this->assertBlackfire($configuration, function () {
            $responder = $this->homeResponder;

            $responder($this->request);
        });
    }
}
