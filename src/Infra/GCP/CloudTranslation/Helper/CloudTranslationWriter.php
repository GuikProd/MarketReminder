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

namespace App\Infra\GCP\CloudTranslation\Helper;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces\CloudTranslationFactoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationValidatorInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class CloudTranslationWriter.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWriter implements CloudTranslationWriterInterface
{
    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * @var CloudTranslationFactoryInterface
     */
    private $cloudTranslationFactory;

    /**
     * @var CloudTranslationValidatorInterface
     */
    private $cloudTranslationValidator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ConnectorInterface $connector,
        CloudTranslationFactoryInterface $cloudTranslationFactory,
        CloudTranslationValidatorInterface $cloudTranslationValidator
    ) {
        $this->connector = $connector;
        $this->cloudTranslationFactory = $cloudTranslationFactory;
        $this->cloudTranslationValidator = $cloudTranslationValidator;
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $locale, string $channel, string $fileName, array $values): bool
    {
        $cacheItem = $this->connector->getAdapter()->getItem($fileName);

        if ($cacheItem->isHit()) {

            $errors = $this->cloudTranslationValidator->validate($cacheItem->get(), $values);

            if (\count($errors) > 0) {
                $this->connector->getAdapter()->invalidateTags($cacheItem->getPreviousTags());

                $this->connector->getAdapter()->deleteItem($cacheItem->getKey());

                return $this->write($locale, $channel, $fileName, $values);
            }

            return false;
        }

        foreach ($values as $item => $value) {
            $this->cloudTranslationFactory->buildCloudTranslationItem(
                $locale,
                $channel,
                $item,
                $value
            );
        }

        $cloudTranslationItem = $this->cloudTranslationFactory->buildCloudTranslation(
            $fileName,
            $channel,
            $this->cloudTranslationFactory->getCloudTranslationItem()
        );

        $cacheItem->set($cloudTranslationItem);
        $cacheItem->tag(Uuid::uuid4()->toString());

        return $this->connector->getAdapter()->save($cacheItem);
    }
}
