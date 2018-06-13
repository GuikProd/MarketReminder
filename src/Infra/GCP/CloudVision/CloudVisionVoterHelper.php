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

use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;

/**
 * Class CloudVisionVoterHelper.
 *
 * This class is used in order to give a vote
 * on a given analyse, the vote is called 10 times (minimal configuration !)
 * in order to ease the decision process.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionVoterHelper implements CloudVisionVoterHelperInterface
{
    /**
     * The labels which are forbidden.
     *
     * (This array is populated via configuration)
     *
     * @var array
     */
    private $forbiddenLabels = [];

    /**
     * The number of vote which found a negative label.
     *
     * @var int
     */
    private $votes = 0;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $forbiddenLabels)
    {
        $this->forbiddenLabels = $forbiddenLabels;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(string $label, int $calls = 10): void
    {
        $startingVote = 0;

        while ($startingVote < $calls) {
            if (in_array($label, $this->forbiddenLabels)) {
                $this->votes += 1;
            }

            $startingVote++;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getVotes(): int
    {
        return $this->votes;
    }
}
