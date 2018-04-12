<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infra\GCP\Bridge\Interfaces;

use Google\Cloud\Vision\VisionClient;

/**
 * Interface CloudVisionBridgeInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudVisionBridgeInterface
{
    /**
     * CloudVisionBridgeInterface constructor.
     *
     * @param string  $visionCredentialsFileName
     * @param string  $visionCredentialsFolder
     */
    public function __construct(
        string $visionCredentialsFileName,
        string $visionCredentialsFolder
    );

    /**
     * @return VisionClient
     */
    public function getVisionClient(): VisionClient;
}
