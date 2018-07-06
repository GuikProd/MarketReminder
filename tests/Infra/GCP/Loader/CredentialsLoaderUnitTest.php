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

namespace App\Tests\Infra\GCP\Loader;

use App\Infra\GCP\Loader\CredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CredentialsLoaderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CredentialsLoaderUnitTest extends TestCase
{
    public function testItImplements()
    {
        $loader = new CredentialsLoader();

        static::assertInstanceOf(
            LoaderInterface::class,
            $loader
        );
    }

    public function testItThrownExceptionWithInvalidFile()
    {
        static::expectException(\InvalidArgumentException::class);

        $loader = new CredentialsLoader();

        $loader->loadJson('toto.json', __DIR__.'/../../../_credentials');

        static::assertArrayNotHasKey('type', $loader->getCredentials());
    }

    public function testItLoadCredentials()
    {
        $loader = new CredentialsLoader();

        $loader->loadJson('credentials.json', __DIR__.'/../../../_credentials');

        static::assertArrayHasKey('type', $loader->getCredentials());
    }
}
