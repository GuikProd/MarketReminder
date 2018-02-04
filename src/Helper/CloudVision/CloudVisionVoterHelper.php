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
class CloudVisionVoterHelper implements CloudVisionVoterHelperInterface
{
    const FORBIDDEN_LABELS = ['drugs', 'gun', 'money', 'sex'];

    /**
     * @var array
     */
    private $labels;

    /**
     * {@inheritdoc}
     */
    public function obtainLabel(array $labels): CloudVisionVoterHelperInterface
    {
        foreach ($labels as $label) {
            $this->labels[] = $label->description();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(): bool
    {
        foreach ($this->labels as $label) {
            if (in_array($label, self::FORBIDDEN_LABELS)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabels(): array
    {
        return $this->labels;
    }
}
