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

namespace App\Bridge;

use Symfony\Component\Finder\Finder;
use Google\Cloud\Core\ServiceBuilder;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;

/**
 * Class CloudStorageBridge.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageBridge implements CloudStorageBridgeInterface
{
    /**
     * @var string
     */
    private $credentialsFolder;

    /**
     * @var \SplFileInfo
     */
    private $credentialsFile;

    /**
     * CloudStorageBridge constructor.
     *
     * @param string $credentialsFolder
     */
    public function __construct(string $credentialsFolder)
    {
        $this->credentialsFolder = $credentialsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceBuilder(): ServiceBuilder
    {
        return new ServiceBuilder([
            'keyFile' => $this->credentialsFile
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function loadCredentialsFile(): CloudStorageBridgeInterface
    {
        $finder = new Finder();

        $files = $finder->in($this->credentialsFolder."/google")
                                        ->files()
                                        ->name('*.json');

        foreach ($files as $file) {
            if ($file->getFilename() === 'credentials.json') {
                $this->credentialsFile = json_decode($file->getContents(), true);
            }
        }

        return $this;
    }
}
