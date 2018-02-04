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

namespace App\Helper\Interfaces\CloudVision;

/**
 * Interface CloudVisionVoterHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudVisionVoterHelperInterface
{
    /**
     * Allow to store every label inside the class for future check and vote.
     *
     * @param array $labels                        The labels that need to be stored.
     *
     * @return  CloudVisionVoterHelperInterface
     */
    public function obtainLabel(array $labels): CloudVisionVoterHelperInterface;

    /**
     * Allow to vote about a label and return the decision.
     *
     * @return bool    Depending on if the label is accepted or not.
     */
    public function vote(): bool;

    /**
     * Allow to get all the labels stored.
     *
     * @return array
     */
    public function getLabels(): array;
}
