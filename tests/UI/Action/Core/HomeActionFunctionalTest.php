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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeActionFunctionalTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @group e2e
 */
final class HomeActionFunctionalTest extends WebTestCase
{
    /**
     * @var Client|null
     */
    private $request = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->request = static::createClient();
    }

    /**
     * @dataProvider provideUrl
     *
     * @param string $url
     */
    public function testStatusCode(string $url)
    {
        $this->request->request('GET', $url);

        static::assertSame(
            Response::HTTP_OK,
            $this->request->getResponse()->getStatusCode()
        );
    }

    /**
     * @dataProvider provideUrlWithContent
     *
     * @param string $url
     * @param string $title
     */
    public function testResponseContent(string $url, string $title)
    {
        $crawler = $this->request->request('GET', $url);

        static::assertGreaterThan(
            0,
            $crawler->filter('title:contains('.$title.')')->count()
        );
    }

    /**
     * @return \Generator
     */
    public function provideUrl()
    {
        yield ['/fr/'];
        yield ['/en/'];
    }

    /**
     * @return \Generator
     */
    public function provideUrlWithContent()
    {
        yield ['/fr/', 'Accueil'];
        yield ['/en/', 'Home'];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->request = null;
    }
}
