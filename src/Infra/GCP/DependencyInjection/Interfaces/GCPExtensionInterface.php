<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: guillaumeloulier
 * Date: 21/06/2018
 * Time: 13:54
 */

namespace App\Infra\GCP\DependencyInjection\Interfaces;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface GCPExtensionInterface
{
    /**
     * @param array $configuration
     * @param ContainerBuilder $containerBuilder
     */
    public function processGlobalConfiguration(array $configuration, ContainerBuilder $containerBuilder): void;
}
