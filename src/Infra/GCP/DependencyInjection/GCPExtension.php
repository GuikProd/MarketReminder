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

namespace App\Infra\GCP\DependencyInjection;

use App\Infra\GCP\CloudPubSub\Bridge\CloudPubSubBridge;
use App\Infra\GCP\CloudPubSub\Bridge\Interfaces\CloudPubSubBridgeInterface;
use App\Infra\GCP\CloudPubSub\Loader\CloudPubSubAbstractCredentialsLoader;
use App\Infra\GCP\CloudPubSub\Loader\Interfaces\CloudPubSubCredentialsLoaderInterface;
use App\Infra\GCP\CloudStorage\Bridge\CloudStorageBridge;
use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageCleanerHelper;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageRetrieverHelper;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageWriterHelper;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageCleanerHelperInterface;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageRetrieverHelperInterface;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\FileSystemConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Domain\Repository\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use App\Infra\GCP\CloudVision\Bridge\CloudVisionBridge;
use App\Infra\GCP\CloudVision\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Infra\GCP\CloudVision\CloudVisionAnalyserHelper;
use App\Infra\GCP\CloudVision\CloudVisionDescriberHelper;
use App\Infra\GCP\CloudVision\CloudVisionVoterHelper;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;
use App\Infra\GCP\DependencyInjection\Interfaces\GCPExtensionInterface;
use App\Infra\GCP\Loader\AbstractCredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class GCPExtension.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class GCPExtension extends Extension implements GCPExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new GCPConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->processGlobalConfiguration($config, $container);
        $this->processPubSubConfiguration($config, $container);
        $this->processStorageConfiguration($config, $container);
        $this->processTranslationConfiguration($config, $container);
        $this->processVisionConfiguration($config, $container);
    }

    /**
     * {@inheritdoc}
     */
    public function processGlobalConfiguration(array $configuration, ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->setParameter('credentials_filename', $configuration['credentials_filename']);
        $containerBuilder->setParameter('credentials_folder', $configuration['credentials_folder']);

        if (!$containerBuilder->hasDefinition(LoaderInterface::class)) {
            $containerBuilder->register(LoaderInterface::class, AbstractCredentialsLoader::class)
                             ->setPublic(false)
                             ->addTag('gcp.loader');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processPubSubConfiguration(array $configuration, ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->setParameter('cloud.pubsub.credentials_filename', $configuration['pubsub']['credentials_filename']);
        $containerBuilder->setParameter('cloud.pubsub.credentials_folder', $configuration['pubsub']['credentials_folder']);

        if (!$containerBuilder->hasDefinition(CloudPubSubCredentialsLoaderInterface::class)) {
            $containerBuilder->register(CloudPubSubCredentialsLoaderInterface::class, CloudPubSubAbstractCredentialsLoader::class)
                             ->setPublic(false)
                             ->addTag('gcp.loader_pubsub');
        }

        $containerBuilder->register(CloudPubSubBridgeInterface::class, CloudPubSubBridge::class)
                         ->addArgument($configuration['pubsub']['credentials_filename'])
                         ->addArgument($configuration['pubsub']['credentials_folder'])
                         ->addArgument($containerBuilder->getDefinition(CloudPubSubCredentialsLoaderInterface::class))
                         ->setPublic(false)
                         ->addTag('gcp.pubsub_bridge');
    }

    /**
     * {@inheritdoc}
     */
    public function processStorageConfiguration(array $configuration, ContainerBuilder $containerBuilder): void
    {
        if ($configuration['storage']['activated']) {
            if (!$containerBuilder->hasDefinition(new Reference(CloudStorageBridgeInterface::class))) {
                $containerBuilder->register(CloudStorageBridgeInterface::class, CloudStorageBridge::class)
                                 ->addArgument($configuration['credentials_filename'])
                                 ->addArgument($configuration['credentials_folder'])
                                 ->addArgument($containerBuilder->getDefinition(LoaderInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.storage_bridge');
            }

            if (!$containerBuilder->hasDefinition(CloudStorageWriterHelperInterface::class)) {
                $containerBuilder->register(CloudStorageWriterHelperInterface::class, CloudStorageWriterHelper::class)
                                 ->addArgument($containerBuilder->getDefinition(CloudStorageBridgeInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.storage');
            }

            if (!$containerBuilder->hasDefinition(CloudStorageRetrieverHelperInterface::class)) {
                $containerBuilder->register(CloudStorageRetrieverHelperInterface::class, CloudStorageRetrieverHelper::class)
                                 ->addArgument($containerBuilder->getDefinition(CloudStorageBridgeInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.storage');
            }

            if (!$containerBuilder->hasDefinition(CloudStorageCleanerHelperInterface::class)) {
                $containerBuilder->register(CloudStorageCleanerHelperInterface::class, CloudStorageCleanerHelper::class)
                                 ->addArgument($containerBuilder->getDefinition(CloudStorageBridgeInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.storage');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processTranslationConfiguration(array $configuration, ContainerBuilder $containerBuilder): void
    {
        if ($configuration['translation']['activated']) {
            if (!$containerBuilder->hasDefinition(new Reference(CloudTranslationBridgeInterface::class))) {
                $containerBuilder->register(CloudTranslationBridgeInterface::class, CloudTranslationBridge::class)
                                 ->addArgument($configuration['credentials_folder'])
                                 ->addArgument($configuration['credentials_filename'])
                                 ->setPublic(false)
                                 ->addTag('gcp.translation_bridge');
            }

            if ('redis' === $configuration['translation']['storage_engine']) {
                if (!$containerBuilder->hasDefinition(RedisConnectorInterface::class)) {
                    $containerBuilder->register(RedisConnectorInterface::class, RedisConnector::class)
                                     ->addArgument(getenv('REDIS_URL'))
                                     ->addArgument(getenv('APP_ENV'))
                                     ->setShared(true)
                                     ->addTag('gcp.translation_connector');
                    $containerBuilder->register(ConnectorInterface::class, ConnectorInterface::class);
                    $containerBuilder->setAlias(ConnectorInterface::class, RedisConnectorInterface::class);
                }

            } elseif ('filesystem' === $configuration['translation']['storage_engine']) {
                if (!$containerBuilder->hasDefinition(FileSystemConnectorInterface::class)) {
                    $containerBuilder->register(FileSystemConnectorInterface::class, FileSystemConnector::class)
                                     ->addArgument(getenv('APP_ENV'))
                                     ->setShared(true)
                                     ->addTag('gcp.translation_connector');
                    $containerBuilder->register(ConnectorInterface::class, ConnectorInterface::class);
                    $containerBuilder->setAlias(ConnectorInterface::class, FileSystemConnectorInterface::class);
                }
            }

            if (!$containerBuilder->hasDefinition(CloudTranslationWriter::class)) {
                $containerBuilder->register(CloudTranslationWriter::class, CloudTranslationWriter::class)
                                 ->addArgument($containerBuilder->findDefinition(ConnectorInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.translation');
                $containerBuilder->setAlias(CloudTranslationWriterInterface::class, CloudTranslationWriter::class);
            }

            if (!$containerBuilder->hasDefinition(CloudTranslationRepositoryInterface::class)) {
                $containerBuilder->register(CloudTranslationRepositoryInterface::class, CloudTranslationRepository::class)
                                 ->addArgument($containerBuilder->findDefinition(ConnectorInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.translation');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processVisionConfiguration(array $configuration, ContainerBuilder $containerBuilder): void
    {
        if ($configuration['vision']['activated']) {
            if (!$containerBuilder->hasDefinition(CloudVisionBridgeInterface::class)) {
                $containerBuilder->register(CloudVisionBridgeInterface::class, CloudVisionBridge::class)
                                 ->addArgument($configuration['credentials_filename'])
                                 ->addArgument($configuration['credentials_folder'])
                                 ->addArgument($containerBuilder->getDefinition(LoaderInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.vision_bridge');
            }

            if (!$containerBuilder->hasDefinition(CloudVisionAnalyserHelperInterface::class)) {
                $containerBuilder->register(CloudVisionAnalyserHelperInterface::class, CloudVisionAnalyserHelper::class)
                                 ->addArgument($containerBuilder->getDefinition(CloudVisionBridgeInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.vision_helper');
            }

            if (!$containerBuilder->hasDefinition(CloudVisionDescriberHelperInterface::class)) {
                $containerBuilder->register(CloudVisionDescriberHelperInterface::class, CloudVisionDescriberHelper::class)
                                 ->addArgument($containerBuilder->getDefinition(CloudVisionBridgeInterface::class))
                                 ->setPublic(false)
                                 ->addTag('gcp.vision_helper');
            }

            if (!$containerBuilder->hasDefinition(CloudVisionVoterHelperInterface::class)) {
                $containerBuilder->register(CloudVisionVoterHelperInterface::class, CloudVisionVoterHelper::class)
                                 ->addArgument($configuration['vision']['forbidden_labels'])
                                 ->setPublic(false)
                                 ->addTag('gcp.vision_helper');
            }
        }
    }
}
