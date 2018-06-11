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

use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;
use App\Infra\GCP\CloudVision\Validator\Interfaces\CloudVisionImageValidatorInterface;

/**
 * Class CloudVisionImageValidator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionImageValidator implements CloudVisionImageValidatorInterface
{
    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudAnalyserHelper;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriber;

    /**
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoter;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper,
        CloudVisionVoterHelperInterface $cloudVisionVoterHelper
    ) {
        $this->cloudAnalyserHelper = $cloudVisionAnalyserHelper;
        $this->cloudVisionDescriber = $cloudVisionDescriberHelper;
        $this->cloudVisionVoter = $cloudVisionVoterHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(\SplFileInfo $image, string $analyseMode): bool
    {
        $analyzedImage = $this->cloudAnalyserHelper->analyse($image->getPathname(), $analyseMode);

        $labels = $this->cloudVisionDescriber->describe($analyzedImage)->labels();

        $this->cloudVisionDescriber->obtainLabel($labels);

        foreach ($this->cloudVisionDescriber->getLabels() as $label) {
            if (!$this->cloudVisionVoter->vote($label)) {
                return false;
            }
        }

        return true;
    }
}
