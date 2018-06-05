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

use App\Tests\TestCase\LocaleTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeActionFunctionalTest.
 */
final class HomeActionFunctionalTest extends LocaleTestCase
{
    /**
     * @var null
     */
    private $client = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider provideLocaleAndTitle
     *
     * @group Functional
     *
     * @param string $locale
     * @param string $title
     */
    public function testAskForHomepage(string $locale, string $title)
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/'.$locale.'/');

        static::assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
        static::assertGreaterThan(
            0,
            $crawler->filter('title:contains('.$title.')')->count()
        );
    }
}
