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

namespace App\Tests\UI\Responder\Core;

use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
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
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeResponderSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var HomeResponderInterface
     */
    private $homeResponder;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

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

        $redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->redisTranslationRepository = new RedisTranslationRepository($redisConnector);
        $this->presenter = new Presenter($this->redisTranslationRepository);
        $this->request = Request::create('/fr/', 'GET');
        $this->twig = static::$kernel->getContainer()->get('twig');

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
