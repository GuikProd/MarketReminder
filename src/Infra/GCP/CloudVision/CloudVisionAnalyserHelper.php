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

namespace App\Infra\GCP\CloudVision;

use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use Google\Cloud\Vision\Image;

/**
 * Class CloudVisionAnalyserHelper.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudVisionAnalyserHelper implements CloudVisionAnalyserHelperInterface
{
    /**
     * @var CloudVisionBridgeInterface
     */
    private $cloudVisionBridgeInterface;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudVisionBridgeInterface $cloudVisionBridgeInterface)
    {
        $this->cloudVisionBridgeInterface = $cloudVisionBridgeInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function analyse(string $imagePath, string $analyseMode): Image
    {
        return $this->cloudVisionBridgeInterface
                    ->getVisionClient()
                    ->image(
                        file_get_contents($imagePath),
                        [$analyseMode]
                    );
    }
}
