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

namespace App\Infra\GCP\CloudVision\Validator\Interfaces;

use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;

/**
 * Interface CloudVisionImageValidatorInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudVisionImageValidatorInterface
{
    /**
     * CloudVisionImageValidatorInterface constructor.
     *
     * @param CloudVisionAnalyserHelperInterface  $cloudVisionAnalyserHelper
     * @param CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper
     * @param CloudVisionVoterHelperInterface     $cloudVisionVoterHelper
     */
    public function __construct(
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper,
        CloudVisionVoterHelperInterface $cloudVisionVoterHelper
    );

    /**
     * @param \SplFileInfo $image The image to validate.
     * @param string $analyseMode
     *
     * @return bool If the image is valid or not.
     */
    public function validate(\SplFileInfo $image, string $analyseMode): bool;
}
