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

namespace App\Infra\GCP\DependencyInjection\Compiler;

use App\Infra\GCP\CloudPubSub\Bridge\CloudPubSubBridge;
use App\Infra\GCP\CloudPubSub\Loader\CloudPubSubAbstractCredentialsLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class GCPCompilerPass.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class GCPCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition(CloudPubSubBridge::class)
                  ->addArgument($container->getParameter('cloud.pubsub.credentials_filename'))
                  ->addArgument($container->getParameter('cloud.pubsub.credentials_folder'))
                  ->addArgument($container->getDefinition(CloudPubSubAbstractCredentialsLoader::class));
    }
}
