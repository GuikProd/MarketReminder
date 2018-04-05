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

namespace App\Application\Validator;

use App\Infra\GCP\CloudVision\CloudVisionVoterHelper;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Application\Validator\Interfaces\ImageContentValidatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper,
        TranslatorInterface $translator
    ) {
        $this->cloudVisionAnalyser = $cloudVisionAnalyserHelper;
        $this->cloudVisionDescriber = $cloudVisionDescriberHelper;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $analysedImage = $this->cloudVisionAnalyser
                              ->analyse(
                                  $value->getPathname(),
                                  'LABEL_DETECTION'
                              );

        $labels = $this->cloudVisionDescriber->describe($analysedImage)->labels();

        $this->cloudVisionDescriber->obtainLabel($labels);

        foreach ($this->cloudVisionDescriber->getLabels() as $label) {
            if (!CloudVisionVoterHelper::vote($label)) {
                $this->context
                     ->buildViolation(
                         $this->translator
                              ->trans($constraint->message, [], 'validators')
                     )->addViolation();
            }
        }
    }
}
