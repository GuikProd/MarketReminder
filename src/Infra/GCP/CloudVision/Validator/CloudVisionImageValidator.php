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

namespace App\Infra\GCP\CloudVision\Validator;

use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;
use App\Infra\GCP\CloudVision\Validator\Interfaces\CloudVisionImageValidatorInterface;

/**
 * Class CloudVisionImageValidator.
 *
 * This class is responsible to validate the content of an image.
 * The check is done via GCP CloudVision API.
 * In order to ease the validation process, the labels are passed
 * to a CloudVisionVoterHelper which should decide if the labels are authorized.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionImageValidator implements CloudVisionImageValidatorInterface
{
    /**
     * Used in order to analyse the image using filesystem access
     * and the analyse mode (defined via configuration or during runtime).
     *
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudAnalyserHelper;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriber;

    /**
     * Used to decide if the label is authorized or not.
     * By default, the Voter is called 10 times in order
     * to optimise the final vote.
     *
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoter;

    /**
     * @var array
     */
    private $violation = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper,
        CloudVisionVoterHelperInterface $cloudVisionVoterHelper
    ) {
        $this->cloudAnalyserHelper  = $cloudVisionAnalyserHelper;
        $this->cloudVisionDescriber = $cloudVisionDescriberHelper;
        $this->cloudVisionVoter     = $cloudVisionVoterHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(\SplFileInfo $image, string $analyseMode): void
    {
        if (\is_null($image)) { return; }

        $analyzedImage = $this->cloudAnalyserHelper->analyse($image->getPathname(), $analyseMode);

        $labels = $this->cloudVisionDescriber->describe($analyzedImage)->labels();

        $this->cloudVisionDescriber->obtainLabel($labels);

        foreach ($this->cloudVisionDescriber->getLabels() as $label) {

            $this->cloudVisionVoter->vote($label);

            if ($this->cloudVisionVoter->getVotes() > 0) {
                $this->violation[] = sprintf(
                    'This label isn\'t authorized, given %s',
                    $label
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getViolations(): array
    {
        return $this->violation;
    }
}
