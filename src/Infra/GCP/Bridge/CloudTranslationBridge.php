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

namespace App\Infra\GCP\Bridge;

use App\Infra\GCP\Bridge\Interfaces\CloudBridgeInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use Symfony\Component\Config\FileLocator;

/**
 * Class CloudTranslationBridge.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationBridge extends AbstractBridge implements CloudTranslationBridgeInterface
{
    /**
     * @var string
     */
    private $translationCredentialsFolder;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $translationCredentialsFolder)
    {
        $this->translationCredentialsFolder = $translationCredentialsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function loadCredentialsFile(): CloudBridgeInterface
    {
        $fileLocator = new FileLocator($this->translationCredentialsFolder);

        $this->credentials = json_decode(
            file_get_contents(
                $fileLocator->locate('credentials.json')
            ), true
        );

        return $this;
    }
}
