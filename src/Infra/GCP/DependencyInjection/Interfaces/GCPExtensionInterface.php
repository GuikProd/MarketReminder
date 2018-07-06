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

namespace App\Infra\GCP\DependencyInjection\Interfaces;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface GCPExtensionInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface GCPExtensionInterface
{
    /**
     * @param array $configuration
     * @param ContainerBuilder $containerBuilder
     */
    public function processGlobalConfiguration(array $configuration, ContainerBuilder $containerBuilder): void;

    /**
     * @param array $configuration
     * @param ContainerBuilder $containerBuilder
     */
    public function processStorageConfiguration(array $configuration, ContainerBuilder $containerBuilder): void;

    /**
     * @param array $configuration
     * @param ContainerBuilder $containerBuilder
     */
    public function processTranslationConfiguration(array $configuration, ContainerBuilder $containerBuilder): void;

    /**
     * @param array $configuration
     * @param ContainerBuilder $containerBuilder
     */
    public function processVisionConfiguration(array $configuration, ContainerBuilder $containerBuilder): void;
}
