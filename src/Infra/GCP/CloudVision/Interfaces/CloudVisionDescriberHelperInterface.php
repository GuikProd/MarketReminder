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

namespace App\Infra\GCP\CloudVision\Interfaces;

use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use Google\Cloud\Vision\Image;
use Google\Cloud\Vision\Annotation;

/**
 * Interface CloudVisionDescriberHelperInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudVisionDescriberHelperInterface
{
    /**
     * CloudVisionDescriberHelperInterface constructor.
     *
     * @param CloudVisionBridgeInterface $cloudVisionBridgeInterface
     */
    public function __construct(CloudVisionBridgeInterface $cloudVisionBridgeInterface);

    /**
     * Allow to return the image attributes.
     *
     * @param Image $analysedImage    The image which's been analysed.
     *
     * @return Annotation             The information linked to the image.
     *
     * @see Annotation                Documentation purpose.
     */
    public function describe(Image $analysedImage): Annotation;

    /**
     * Allow to store every label inside the class for future check and vote.
     *
     * @param array $labels  The labels that need to be stored.
     *
     * @return  CloudVisionDescriberHelperInterface
     */
    public function obtainLabel(array $labels): self;

    /**
     * @return array
     */
    public function getLabels(): array;
}
