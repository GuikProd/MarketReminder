<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Mutators;

use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

/**
 * Class ImageMutator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ImageMutator implements MutationInterface
{
    public function createImage(Argument $argument)
    {
    }
}
