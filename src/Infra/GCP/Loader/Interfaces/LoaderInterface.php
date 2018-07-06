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

namespace App\Infra\GCP\Loader\Interfaces;

/**
 * Interface LoaderInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface LoaderInterface
{
    /**
     * @param string $filename
     * @param string $folder
     */
    public function loadJson(string $filename, string $folder): void;

    /**
     * Allow to close the actual connexion by erasing the credentials.
     */
    public function closeConnexion(): void;

    /**
     * @return array
     */
    public function getCredentials(): array;
}
