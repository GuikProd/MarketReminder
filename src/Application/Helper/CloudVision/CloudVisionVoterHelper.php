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

use App\Application\Helper\CloudVision\Interfaces\CloudVisionVoterHelperInterface;

/**
 * Class CloudVisionVoterHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class CloudVisionVoterHelper implements CloudVisionVoterHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public static function vote(string $label): bool
    {
        return !in_array($label, self::FORBIDDEN_LABELS);
    }
}
