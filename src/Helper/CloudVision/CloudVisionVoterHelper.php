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

use App\Helper\Interfaces\CloudVision\CloudVisionVoterHelperInterface;

/**
 * Class CloudVisionVoterHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class CloudVisionVoterHelper implements CloudVisionVoterHelperInterface
{
    const FORBIDDEN_LABELS = ['drugs', 'gun', 'money', 'sex'];

    /**
     * {@inheritdoc}
     */
    public static function vote(string $label): bool
    {
        return !in_array($label, self::FORBIDDEN_LABELS);
    }
}
