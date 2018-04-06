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

namespace App\Application\Validator\Interfaces;

use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface ImageContentValidatorInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageContentValidatorInterface
{
    /**
     * ImageContentValidatorInterface constructor.
     *
     * @param CloudVisionAnalyserHelperInterface   $cloudVisionAnalyserHelper
     * @param CloudVisionDescriberHelperInterface  $cloudVisionDescriberHelper
     * @param TranslatorInterface                  $translator
     */
    public function __construct(
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyserHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriberHelper,
        TranslatorInterface $translator
    );
}
