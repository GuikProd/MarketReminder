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

namespace App\Application\Helper\CloudVision;

use App\Application\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Application\Helper\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use Google\Cloud\Vision\Annotation;
use Google\Cloud\Vision\Image;

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
