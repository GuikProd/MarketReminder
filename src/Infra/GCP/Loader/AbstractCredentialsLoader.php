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

namespace App\Infra\GCP\Loader;

use App\Infra\GCP\Loader\Interfaces\LoaderInterface;

/**
 * Class AbstractCredentialsLoader.
 *
 * Allow to find, parse and load the credentials.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class AbstractCredentialsLoader implements LoaderInterface
{
    /**
     * The credentials once the json file has been loaded and decoded.
     *
     * @var array
     */
    private $credentials = [];

    /**
     * {@inheritdoc}
     */
    public function loadJson(string $filename, string $folder): void
    {
        if (!file_exists($folder.'/'.$filename)) {
            throw new \InvalidArgumentException(
                sprintf('The credentials file should exist, trying to access %s', $folder.'/'.$filename)
            );
        }

        $this->credentials = json_decode(
            file_get_contents($folder.'/'.$filename), true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function closeConnexion(): void
    {
        $this->credentials = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): array
    {
        return $this->credentials;
    }
}
