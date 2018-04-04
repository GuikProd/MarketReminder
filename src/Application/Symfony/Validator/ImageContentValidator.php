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

namespace App\Application\Symfony\Validator;

use App\Application\Helper\CloudVision\CloudVisionVoterHelper;
use App\Application\Helper\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Application\Helper\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Application\Symfony\Validator\Interfaces\ImageContentValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ImageContentValidator.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageContentValidator extends ConstraintValidator implements ImageContentValidatorInterface
{
    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyser;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriber;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper
    ) {
        $this->cloudVisionAnalyser = $cloudVisionAnalyserHelper;
        $this->cloudVisionDescriber = $cloudVisionDescriberHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $analysedImage = $this->cloudVisionAnalyser
                              ->analyse(
                                  $value->getPathname().$value->getBasename,
                                  'LABEL_DETECTION'
                              );

        $labels = $this->cloudVisionDescriber->describe($analysedImage)->labels();

        $this->cloudVisionDescriber->obtainLabel($labels);

        foreach ($this->cloudVisionDescriber->getLabels() as $label) {
            if (!CloudVisionVoterHelper::vote($label)) {
                $this->context->buildViolation($constraint->payload)
                              ->addViolation();
            }
        }
    }
}
