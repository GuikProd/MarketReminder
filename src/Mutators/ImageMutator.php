<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Mutators;

use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

/**
 * Class ImageMutator.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageMutator implements MutationInterface
{
    public function createImage(Argument $argument)
    {
    }
}
