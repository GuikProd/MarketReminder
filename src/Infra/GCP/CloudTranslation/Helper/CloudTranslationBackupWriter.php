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
use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslation;
use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationBackupWriterInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class CloudTranslationBackupWriter.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBackupWriter implements CloudTranslationBackupWriterInterface
{
    /**
     * @var ConnectorInterface
     */
    private $backupConnector;

    /**
     * {@inheritdoc}
     */
    public function __construct(ConnectorInterface $backupConnector)
    {
        $this->backupConnector = $backupConnector;
    }

    /**
     * {@inheritdoc}
     */
    public function warmBackUp(string $channel, string $locale, array $newValues, string $format = 'yaml'): bool
    {
        $backupItem = $this->backupConnector->getAdapter()->getItem($channel.'.'.$locale.'.'.$format);

        if (!$backupItem->get()) {

            $entries = [];
            $tag = Uuid::uuid4()->toString();

            foreach ($newValues as $item => $value) {
                $entries[] = new CloudTranslationItem([
                    '_locale' => $locale,
                    'channel' => $channel,
                    'tag' => $tag,
                    'key' => $item,
                    'value' => $value
                ]);
            }

            $backupItem->set(new CloudTranslation(
                $channel.'.'.$locale.'.'.$format,
                $channel,
                $entries
            ));
            $backupItem->tag($tag);

            return $this->backupConnector->getAdapter()->save($backupItem);
        }

        $defaultKeys = [];
        $defaultValues = [];
        $toCheckKeys = [];
        $toCheckValues = [];

        foreach ($backupItem->get()->getItems() as $key => $value) {
            $defaultKeys[] = $value->getKey();
            $defaultValues[] = $value->getValue();
        }

        foreach ($newValues as $newKey => $newValue) {
            $toCheckKeys[] = $newKey;
            $toCheckValues[] = $newValue;
        }

        $toCheckArray = array_combine($defaultKeys, $defaultValues);
        $newArray = array_combine($toCheckKeys, $toCheckValues);

        if (\count(array_diff($toCheckArray, $newArray)) > 0) {
            $this->backupConnector->getAdapter()->invalidatetags($backupItem->getPreviousTags());
            $this->backupConnector->getAdapter()->deleteItem($backupItem->getKey());

            return $this->warmBackUp($channel, $locale, $newValues);
        }

        return false;
    }
}
