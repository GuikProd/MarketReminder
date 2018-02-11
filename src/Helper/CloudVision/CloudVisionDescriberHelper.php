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

namespace App\Helper\CloudVision;

use Google\Cloud\Vision\Image;
use Google\Cloud\Vision\Annotation;
use App\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;

/**
 * Class CloudVisionDescriberHelper.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudVisionDescriberHelper implements CloudVisionDescriberHelperInterface
{
    /**
     * @var CloudVisionBridgeInterface
     */
    private $cloudVisionBridgeInterface;

    /**
     * @var array
     */
    private $labels;

    /**
     * CloudVisionAnalyserHelper constructor.
     *
     * @param CloudVisionBridgeInterface $cloudVisionBridgeInterface
     */
    public function __construct(CloudVisionBridgeInterface $cloudVisionBridgeInterface)
    {
        $this->cloudVisionBridgeInterface = $cloudVisionBridgeInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function describe(Image $analysedImage): Annotation
    {
        return $this->cloudVisionBridgeInterface
                    ->loadCredentialsFile()
                    ->getServiceBuilder()
                    ->vision()
                    ->annotate($analysedImage);
    }

    /**
     * {@inheritdoc}
     */
    public function obtainLabel(array $labels): CloudVisionDescriberHelperInterface
    {
        foreach ($labels as $label) {
            $this->labels[] = $label->description();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabels(): array
    {
        return $this->labels;
    }
}
