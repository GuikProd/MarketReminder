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

namespace App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces;

/**
 * Interface CloudTranslationYamlParserInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationYamlParserInterface
{
    /**
     * @param string $folder
     * @param string $filename
     */
    public function parseYaml(string $folder, string $filename): void;

    /**
     * @return array
     */
    public function getKeys(): array;

    /**
     * @return array
     */
    public function getValues(): array;
}
