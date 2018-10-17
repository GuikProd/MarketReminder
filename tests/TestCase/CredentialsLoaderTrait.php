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

use App\Infra\GCP\Loader\AbstractCredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;

/**
 * Trait CredentialsLoaderTrait.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
trait CredentialsLoaderTrait
{
    /**
     * @var LoaderInterface
     */
    protected $loader;

    public function createCredentialsLoader()
    {
        $this->loader = new AbstractCredentialsLoader();
    }
}
