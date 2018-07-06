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

namespace App\Tests\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LocaleTestCase.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class LocaleTestCase extends WebTestCase
{
    /**
     * @return \Generator
     */
    public function provideLocaleAndTitle()
    {
        yield array('fr', 'Accueil');
        yield array('en', 'Home');
    }
}
