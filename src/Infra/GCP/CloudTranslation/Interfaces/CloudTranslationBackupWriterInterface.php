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

namespace App\Infra\GCP\CloudTranslation\Interfaces;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\BackupConnectorInterface;

/**
 * Interface CloudTranslationBackupWriterInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationBackupWriterInterface
{
    /**
     * CloudTranslationBackupWriterInterface constructor.
     *
     * @param BackupConnectorInterface $backupConnector
     */
    public function __construct(BackupConnectorInterface $backupConnector);

    /**
     * Allow to create a backup of the default cache item.
     *
     * @param string $channel   The channel used by the item to back up.
     * @param string $locale    The locale used by the item to back up.
     * @param array  $newValues The new values to back up.
     * @param string $format    The format of the content stored (default to YAML).
     *
     * @return bool If the back up has succeed or not.
     */
    public function warmBackUp(string $channel, string $locale, array $newValues, string $format = 'yaml'): bool;
}
